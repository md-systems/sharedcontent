<?php
 
/**
 * @file
 * Contains \Drupal\sharedcontent\IndexingServiceBase.
 */

namespace Drupal\sharedcontent\Services;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\file\FileInterface;
use Drupal\sharedcontent\IndexInterface;

/**
 * Class IndexingServiceBase
 *
 * Provides base functionality to maintain index records.
 *
 * @package Drupal\sharedcontent\Services
 */
abstract class IndexingServiceBase implements IndexingServiceInterface {

  /**
   * The query factory.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $queryFactory;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManager
   */
  protected $entityManager;

  /**
   * Constructs an Indexing instance.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $query_factory
   *   The query factory.
   * @param \Drupal\Core\Entity\EntityManager $entity_manager
   *   The entity manager.
   */
  public function __construct(QueryFactory $query_factory, EntityManager $entity_manager) {
    $this->queryFactory = $query_factory;
    $this->entityManager = $entity_manager;
  }

  /**
   * Check for existing index record.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity the index is checked for.
   *
   * @return bool
   *   TRUE if there is an existing index record, FALSE otherwise.
   */
  public function exists(EntityInterface $entity) {
    $query = $this->queryFactory->get('sharedcontent_index');
    $query->andConditionGroup()
      ->condition('entity_uuid', $entity->uuid())
      ->condition('entity_type', $entity->getEntityTypeId())
      ->condition('origin', IndexInterface::BUNDLE_LOCAL);

    $result = $query->execute();
    $exists = !empty($result);
    return $exists;
  }

  /**
   * Load an sharedcontent_index by entity.
   *
   * For we only need this locally, this method will only return an index record
   * of type \Drupal\sharedcontent\IndexInterface::BUNDLE_LOCAL.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to search index record for.
   *
   * @return \Drupal\sharedcontent\IndexInterface|null
   *   The index object, or NULL if there is no index for the given entity.
   */
  public function load(EntityInterface $entity) {
    $results = $this->entityManager
      ->getStorageController('sharedcontent_index')
      ->loadByProperties(array(
        'entity_uuid' => $entity->uuid(),
        'entity_type' => $entity->getEntityTypeId(),
        'origin' => IndexInterface::BUNDLE_LOCAL,
        'langcode' => $entity->language()->id,
      )
    );
    return array_shift($results);
  }

  /**
   * Update indexed data.
   *
   * @param IndexInterface $index
   *   The index record to update.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to take the updated data from.
   */
  protected function update(IndexInterface $index, EntityInterface $entity) {
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
    $url = $entity->url('canonical', array('absolute' => TRUE));
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
    $url = $index->getUrl();
    if (empty($url)) {
      $index->setStatus(IndexInterface::STATUS_NOT_REACHABLE);
    }
    else {
      $index->setStatus(IndexInterface::STATUS_VISIBLE);
    }
  }
}
