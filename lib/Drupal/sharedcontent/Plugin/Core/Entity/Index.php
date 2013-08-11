<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Plugin\Core\Entity\Index.
 */

namespace Drupal\sharedcontent\Plugin\Core\Entity;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\Annotation\EntityType;
use Drupal\Core\Entity\EntityNG;
use Drupal\Core\Entity\EntityStorageControllerInterface;
use Drupal\sharedcontent\IndexInterface;

/*
 * 'views controller class' => 'SharedContentIndexViewsController',
 * "render" = "Drupal\node\NodeRenderController", --> sharedcontent_index_access
 * "access" = "Drupal\node\NodeAccessController",
 * "translation" = "Drupal\node\NodeTranslationController"
 */

/**
 * Defines the Shared Content Index entity class
 *
 * Container for meta data about another entity.
 *
 * @EntityType(
 *   id = "sharedcontent_index",
 *   label = @Translation("Shared Content Index"),
 *   bundle_label = @Translation("Origin"),
 *   module = "sharedcontent",
 *   controllers = {
 *     "storage" = "Drupal\sharedcontent\Controller\IndexStorageController"
 *   },
 *   base_table = "sharedcontent_index",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "origin",
 *     "label" = "title",
 *     "uuid" = "uuid"
 *   },
 *   bundle_keys = {
 *     "bundle" = "origin"
 *   }
 * )
 */
class Index extends EntityNG implements IndexInterface {

  /**
   * {@inheritdoc}
   */
  public function getConnectionName() {
    return $this->get('connection_name')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setConnectionName($connection_name) {
    $this->set('connection_name', $connection_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setBundle($origin) {
    $this->set('origin', $origin);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setUuid($uuid) {
    $this->set('uuid', $uuid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getParentUuid() {
    return $this->get('uuid_parent')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setParentUuid($uuid) {
    $this->set('uuid_parent', $uuid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityId() {
    return $this->get('entity_id')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setEntityId($id) {
    $this->set('entity_id', $id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityType() {
    return $this->get('entity_type')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setEntityType($type) {
    $this->set('entity_type', $type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityBundle() {
    return $this->get('entity_bundle')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setEntityBundle($bundle) {
    $this->set('entity_bundle', $bundle);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getUrl() {
    return $this->get('url')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setUrl($url) {
    $this->set('url', $url);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getlanguage() {
    return $this->get('language')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setLanguage($language) {
    $this->set('language', $language);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTranslationSetId() {
    return $this->get('translationset_id')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setTranslationSetId($id) {
    $this->set('translationset_id', $id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getKeywords() {
    return $this->get('keywords')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setKeywords($keywords) {
    $this->set('keywords', $keywords);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTags() {
    return $this->get('tags')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setTags($tags) {
    $this->set('tags', $tags);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isLinkable() {
    if ($this->bundle() != SHAREDCONTENT_INDEX_BUNDLE_LOCAL) {
      return FALSE;
    }
    $field_names = sharedcontent_client_get_all_shared_content_field_names();
    $instances = field_info_instances($this->getEntityType(), $this->getEntityBundle());
    foreach ($field_names as $field_name) {
      if (array_key_exists($field_name, $instances)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isVisible() {
    return $this->get('status')->getValue() == SHAREDCONTENT_INDEX_STATUS_LINKABLE;
  }

  /**
   * {@inheritdoc}
   */
  public function isReachable() {
    return $this->get('status')->getValue() != SHAREDCONTENT_INDEX_STATUS_UNREACHABLE;
  }

  /**
   * {@inheritdoc}
   */
  public function getStatus() {
    return $this->get('status')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setStatus($status) {
    $this->set('status', $status);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityCreated() {
    return $this->get('entity_created')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setEntityCreated($created) {
    $this->set('entity_created', $created);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityChanged() {
    return $this->get('entity_changed')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setEntityChanged($changed) {
    $this->set('entity_changed', $changed);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreated() {
    return $this->get('created')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function getChanged() {
    return $this->get('changed')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function getAccessibility() {
    return $this->get('accessibility')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setAccessibility($accessibility) {
    $this->set('accessibility', $accessibility);
    return $this;
  }

  /**
   * Exposed attributes
   *
   * A list of attributes that are meant to be exposed other systems.
   *
   * @var array
   */
  protected static $exposed_attributes = array(
    'uuid',
    'entity_id',
    'entity_type',
    'entity_bundle',
    'title',
    'keywords',
    'tags',
    'language',
    'translationset_id',
    'status',
    'url',
    'parent_uuid',
    'entity_created',
    'entity_changed',
  );

  /**
   * Merges data to this object.
   *
   * @param array $data
   *   Associative array with the data to be merge to this object.
   *
   * @return bool
   *   TRUE if the merge led to a data change, FALSE otherwise.
   */
  public function merge(array $data) {
    $updated = FALSE;
    foreach ($data as $key => $value) {
      if (in_array($key, Index::$exposed_attributes)
        && $this->get($key)->getValue() != $value
      ) {
        $this->set($key, $value);
        $updated = TRUE;
      }
    }
    return $updated;
  }

  /**
   * Exposed attributes.
   *
   * Get an object containing just the attributes that are meant to be
   * exposed to other systems.
   *
   * @return stdClass
   *   Standard object containing the exposed attributes.
   */
  public function getExposedAttributes() {
    $exposed = new stdClass();
    foreach (Index::$exposed_attributes as $attribute) {
      $exposed->$attribute = $this->get($attribute)->getValue();
    }
    return $exposed;
  }

  /**
   * Overrides Entity::defaultUri().
   */
  public function uri() {
    $uri = parent::uri();
    $uri['path'] = $this->getUrl();
    return $uri;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageControllerInterface $storage_controller) {
    $this->set('changed', REQUEST_TIME);
    parent::preSave($storage_controller);
  }

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageControllerInterface $storage_controller, array &$values) {
    $values += array(
      'status' => SHAREDCONTENT_INDEX_STATUS_VISIBLE,
      'accessibility' => SHAREDCONTENT_INDEX_ACCESSIBILITY_PUBLIC,
      'origin' => SHAREDCONTENT_INDEX_BUNDLE_LOCAL,
      'connection_name' => SHAREDCONTENT_LOCAL_CONNECTION_NAME,
      'created' => REQUEST_TIME,
    );
    parent::preCreate($storage_controller, $values);
  }
}
