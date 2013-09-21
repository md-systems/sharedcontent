<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Plugin\Core\Entity\Index.
 */

namespace Drupal\sharedcontent\Entity;

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
  public function getChangedTime() {
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
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions($entity_type) {
    $properties['id'] = array(
      'label' => t('Index ID'),
      'description' => t('The index ID.'),
      'type' => 'integer_field',
      'read-only' => TRUE,
    );
    $properties['uuid'] = array(
      'label' => t('UUID'),
      'description' => t('The index UUID.'),
      'type' => 'uuid_field',
    );
    $properties['parent_uuid'] = array(
      'label' => t('Parent UUID'),
      'description' => t('The UUID of the parent index.'),
      'type' => 'uuid_field',
    );
    $properties['origin'] = array(
      'label' => t('Bundle'),
      'description' => t('The origin of the index.'),
      'type' => 'string_field',
    );
    $properties['connection_name'] = array(
      'label' => t('Site'),
      'description' => t('The originating site of the indexed entity.'),
      'type' => 'string_field',
    );
//    $properties['connection_name']['options list'] = 'sharedcontent_get_connection_labels';
    $properties['entity_id'] = array(
      'label' => t('Entity ID'),
      'description' => t('ID of the indexed entity.'),
      'type' => 'integer_field',
    );
    $properties['entity_type'] = array(
      'label' => t('Entity type'),
      'description' => t('Type of the indexed entity.'),
      'type' => 'string_field',
    );
    $properties['entity_bundle'] = array(
      'label' => t('Entity bundle'),
      'description' => t('Bundle of the indexed entity.'),
      'type' => 'string_field',
    );
//    $properties['entity_bundle']['options list'] = 'sharedcontent_get_all_entity_bundle_labels';
    $properties['title'] = array(
      'label' => t('Title'),
      'description' => t('Title of the indexed entity.'),
      'type' => 'string_field',
    );
    $properties['language'] = array(
      'label' => t('Language'),
      'description' => t('Language of the indexed entity.'),
      'type' => 'language_field',
    );
//    $properties['language']['options list'] = 'entity_metadata_language_list';
    $properties['translationset_id'] = array(
      'label' => t('Translation set ID'),
      'description' => t('ID of the translation set the indexed entity belongs to.'),
      'type' => 'integer_field',
    );
    $properties['keywords'] = array(
      'label' => t('Keywords'),
      'description' => t('Keywords for the indexed entity.'),
      'type' => 'string_field',
    );
    $properties['tags'] = array(
      'label' => t('Generated Keywords'),
      'description' => t('Auto generated Keywords for the indexed entity.'),
      'type' => 'string_field',
    );
    $properties['url'] = array(
      'label' => t('URL'),
      'description' => t('The url of the indexed entity.'),
      'type' => 'uri_field',
    );
    $properties['status'] = array(
      'label' => t('Status'),
      'description' => t('Status.'),
      'type' => 'integer_field',
    );
//    $properties['status']['options list'] = 'sharedcontent_get_index_status_labels';
    $properties['accessibility'] = array(
      'label' => t('Accessibility'),
      'description' => t('Indicates the degree the indexed entity is accessible.'),
      'type' => 'integer_field',
    );
    $properties['entity_created'] = array(
      'label' => t('Entity created'),
      'description' => t('Creation date of the indexed entity.'),
      'type' => 'integer_field',
    );
    $properties['entity_changed'] = array(
      'label' => t('Entity changed'),
      'description' => t('Changed date of the indexed entity.'),
      'type' => 'integer_field',
    );
    $properties['created'] = array(
      'label' => t('Created'),
      'description' => t('Creation date of the index record.'),
      'type' => 'integer_field',
      'read-only' => TRUE,
    );
    $properties['changed'] = array(
      'label' => t('Changed'),
      'description' => t('Changed date of the index record.'),
      'type' => 'integer_field',
      'property_constraints' => array(
        'value' => array('EntityChanged' => array()),
      ),
    );
    $properties['is_linkable'] = array(
      'label' => t('Is Linkable'),
      'description' => t('Indicates if the index record can be linked.'),
      'type' => 'boolean_field',
      'read-only' => TRUE,
    );
//    // Add a calculated property for each flag associated with this entity.
//    if (module_exists('flag')) {
//      foreach (flag_get_flags('sharedcontent_index') as $key => $flag) {
//        $flag_info = array(
//          'label' => $flag->title,
//          'type' => 'boolean',
//          'getter callback' => 'sharedcontent_flag_value_get',
//          'computed' => TRUE,
//          // There might come the situation when we want to display this
//          // attributes inside views. If this is the cas uncomment the
//          // following line will allow to do so.
//          // 'entity views field' => TRUE,
//        );
//        $properties[$key] = $flag_info;
//      }
//    }
    return $properties;
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
    parent::preSave($storage_controller);

    // Before saving the index, set changed time.
    $this->get('changed')->setValue(REQUEST_TIME);
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
