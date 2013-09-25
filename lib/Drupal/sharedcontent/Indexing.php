<?php
 
/**
 * @file
 * Contains \Drupal\sharedcontent\Indexing.
 */

namespace Drupal\sharedcontent;

use Drupal\Core\Entity\EntityInterface;
use Drupal\file\FileInterface;
use Drupal\sharedcontent\Exception\IndexingException;

/**
 * Class Indexing
 *
 * Provides functionality to maintain index records.
 *
 * @package Drupal\sharedcontent
 */
class Indexing {

  /**
   * Checks if an entity is indexable.
   *
   * Whether or not an entity can be indexed is retrieved from configuration and
   * is based on the entity type and bundle.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to check.
   *
   * @return bool
   *   TRUE if the entity is indexable, FALSE otherwise.
   */
  public function isIndexable(EntityInterface $entity) {
    $key = $this->settingsKey($entity->entityType(), $entity->bundle());
    $indexable = \Drupal::config('sharedcontent.indexables')->get($key);
    return $indexable ? TRUE : FALSE;
  }

  /**
   * Configure indexability of an entity.
   *
   * Set the indexability for a entity bundle.
   *
   * @param string $entity_type
   *   The entity type to be configured.
   * @param string $bundle
   *   The entity bundle to be configured.
   * @param bool $value
   *   Whether or not the entity bundle should be indexable.
   */
  public function setIndexable($entity_type, $bundle, $value) {
    $key = $this->settingsKey($entity_type, $bundle);
    \Drupal::config('sharedcontent.indexables')->set($key, $value)->save();
  }

  /**
   * Configure indexability of an entity.
   *
   * Set the indexability for a entity bundle based on an entity.
   *
   * @param EntityInterface $entity
   *   The entity which bundle ist to be configured.
   * @param bool $value
   *   Whether or not the entity bundle should be indexable.
   */
  public function setIndexableByEntity(EntityInterface $entity, $value) {
    $this->setIndexable($entity->entityType(), $entity->bundle(), $value);
  }

  /**
   * Helper for creating a settings key.
   *
   * @param string $entity_type
   *   An entity type.
   * @param string $bundle
   *   An entity bundle
   *
   * @return string
   *   The resulting settings key.
   */
  protected function settingsKey($entity_type, $bundle) {
    $entity_type = preg_replace('/[^0-9a-zA-Z_]/', "_", $entity_type);
    $bundle = preg_replace('/[^0-9a-zA-Z_]/', "_", $bundle);
    return $entity_type . '.' . $bundle . '.indexed';
  }

  /**
   * Index an entity.
   *
   * Creates or updates an index record for the given entity.
   *
   * @param EntityInterface $entity
   *   The entity to be indexed.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The created or updated index record.
   *
   * @throws \Drupal\sharedcontent\Exception\IndexingException
   *
   * @todo Trigger rules invocation once rules got ported.
   */
  public function indexEntity(EntityInterface $entity) {
    $index = sharedcontent_index_load_by_entity($entity);
    $op = 'update';
    if (!$index) {
      // Create new record.
      $index = entity_create('sharedcontent_index', array(
        'entity_uuid' => $entity->uuid(),
        'entity_type' => $entity->entityType(),
        'entity_bundle' => $entity->bundle(),
        'origin' => IndexInterface::BUNDLE_LOCAL,
      ));
      $op = 'create';
    }

    $this->updateIndexData($index, $entity);

    // Trigger alter hook and rules event allowing other parties to alter
    // the index to their likings.
    \Drupal::moduleHandler()->alter('sharedcontent_index', $index, $op);
//    if (function_exists('rules_invoke_event')) {
//      $event = 'sharedcontent_index_is_being_' . $op . 'd';
//      rules_invoke_event($event, $index, $wrapped_entity);
//    }

    $index->save();
    return $index;
  }

  public function indexQueuedEntity(array $data) {
    $entity = entity_load($data['type'], $data['entity_id']);
    if ($entity) {
      try {
        $this->indexEntity($entity);
      }
      catch (IndexingException $e) {
        if ($e->getCode() == SHAREDCONTENT_ERROR_NO_PARENT_INDEX
          && (empty($data['count']) || $data['count'] < 1)
        ) {
          // We do not have control about the order the items get indexed.
          // If we do come here the first time we do a reschedule the first
          // time expecting the parent index record to be generated later
          // during this cron run.
          $data['count'] = 1;
          DrupalQueue::get('sharedcontent_indexing')->createItem($data);
        }
        else {
          sharedcontent_event_save('sharedcontent', __FUNCTION__, $e->getMessage(), $e->data, array('severity' => WATCHDOG_ERROR));
        }
      }
    }
    else {
      sharedcontent_event_save('sharedcontent', __FUNCTION__, 'Failed to load entity', $data, array('severity' => WATCHDOG_WARNING));
    }
  }

  /**
   * Index a deleted entity.
   *
   * Index records are never deleted. When an idnexed entity gets deleted the
   * index status is set to 'not reachable'.
   *
   * @param EntityInterface $entity
   *   The deleted entity.
   */
  public function indexDeletedEntity(EntityInterface $entity) {
    if (sharedcontent_index_exists($entity)) {
      $index = sharedcontent_index_load_by_entity($entity);
      // Make sure we got the latest data.
      $this->updateIndexData($index, $entity);
      $index->setStatus(IndexInterface::STATUS_NOT_REACHABLE);
      $index->save();
    }
  }

  /**
   * Update indexed data.
   *
   * @param IndexInterface $index
   *   The index record to update.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to take the updated data from.
   */
  protected function updateIndexData(IndexInterface $index, EntityInterface $entity) {
    $index->setLangcode($entity->language()->id);
    $index->setTitle($entity->label());
    $this->updateUrl($index, $entity);
    $this->updateAccessibility($index, $entity);
    $this->updateCreatedTime($index, $entity);
    $this->updateChangedTime($index, $entity);
  }

  /**
   * Update url from entity.
   *
   * @param IndexInterface $index
   *   The index record to update.
   * @param EntityInterface $entity
   *   The entity to index the url from.
   */
  protected function updateUrl(IndexInterface $index, EntityInterface $entity) {
    // Get the entities absolute url.
    $uri = $entity->uri();
    $uri['options']['absolute'] = TRUE;
    $url = isset($uri['path']) ? url($uri['path'], $uri['options']) : '';
    if ($entity instanceof FileInterface) {
      $url = file_create_url($entity->getFileUri());
    }
    $index->setUrl($url);
    $this->updateStatus($index, $entity);
  }

  /**
   * Update createdTime from entity.
   *
   * The creation date is only updated if it was not previously set.
   *
   * @param IndexInterface $index
   *   The index record to update.
   * @param EntityInterface $entity
   *   The entity to index the createdTime from.
   */
  protected function updateCreatedTime(IndexInterface $index, EntityInterface $entity) {
    $created = $index->getEntityCreatedTime();
    if (empty($created)) {
      if (method_exists($entity, 'getCreatedTime')) {
        $index->setEntityCreatedTime($entity->getCreatedTime());
      }
      elseif (!empty($entity->created)) {
        $index->setEntityCreatedTime($entity->created->value);
      }
      elseif (!empty($entity->timestamp)) {
        $index->setEntityCreatedTime($entity->timestamp->value);
      }
      else {
        $index->setEntityCreatedTime(REQUEST_TIME);
      }
    }
  }

  /**
   * Update changedTime from entity.
   *
   * @param IndexInterface $index
   *   The index record to update.
   * @param EntityInterface $entity
   *   The entity to index the changedTime from.
   */
  protected function updateChangedTime(IndexInterface $index, EntityInterface $entity) {
    if ($entity instanceof EntityChangedInterface) {
      $index->setEntityChangedTime($entity->getChangedTime());
    }
    elseif (!empty($entity->changed)) {
      $index->setEntityChangedTime($entity->changed->value);
    }
    elseif (!empty($entity->timestamp)) {
      $index->setEntityChangedTime($entity->timestamp->value);
    }
    else {
      $index->setEntityChangedTime($index->getCreatedTime());
    }
  }

  /**
   * Update accessibility from entity.
   *
   * The accessibility is calculated based on the view permission for anonymous.
   *
   * @param IndexInterface $index
   *   The index record to update.
   * @param EntityInterface $entity
   *   The entity to index the accessibility from.
   */
  protected function updateAccessibility(IndexInterface $index, EntityInterface $entity) {
    $accessible = $entity->access('view', drupal_anonymous_user()) ? IndexInterface::ACCESSIBILITY_PUBLIC : IndexInterface::ACCESSIBILITY_RESTRICTED;
    $index->setAccessibility($accessible);
  }

  /**
   * Update status from entity.
   *
   * The status is calculated based on url.
   *
   * @param IndexInterface $index
   *   The index record to update.
   * @param EntityInterface $entity
   *   The entity to index the accessibility from.
   *
   * @see \Drupal\sharedcontent\Entity\$this->updateUrl()
   */
  protected function updateStatus(IndexInterface $index, EntityInterface $entity) {
    if (empty($index->get('url')->value)) {
      $index->setStatus(IndexInterface::STATUS_NOT_REACHABLE);
    }
    else {
      $index->setStatus(IndexInterface::STATUS_VISIBLE);
    }
  }
}
