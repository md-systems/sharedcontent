<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Plugin\Core\Entity\Index.
 */

namespace Drupal\sharedcontent\Entity;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\Annotation\EntityType;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageControllerInterface;
use Drupal\Core\Field\FieldDefinition;
use Drupal\file\FileInterface;
use Drupal\sharedcontent\IndexInterface;

/*
 * 'views controller class' => 'SharedContentIndexViewsController',
 * "render" = "Drupal\node\NodeRenderController", --> sharedcontent_index_access
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
 *     "storage" = "Drupal\Core\Entity\FieldableDatabaseStorageController",
 *     "access" = "Drupal\sharedcontent\Controller\IndexAccessController"
 *   },
 *   base_table = "sharedcontent_index",
 *   fieldable = TRUE,
 *   translatable = FALSE,
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
class Index extends ContentEntityBase implements IndexInterface {

  /**
   * {@inheritdoc}
   */
  public function getConnectionName() {
    return $this->get('connection_name')->value;
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
    return $this->get('parent_uuid')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setParentUuid($uuid) {
    $this->set('parent_uuid', $uuid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityUuid() {
    return $this->get('entity_uuid')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEntityId($id) {
    $this->set('entity_uuid', $id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getIndexedEntityTypeId() {
    return $this->get('entity_type')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setIndexedEntityTypeId($type) {
    $this->set('entity_type', $type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityBundle() {
    return $this->get('entity_bundle')->value;
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
    return $this->get('title')->value;
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
    return $this->get('url')->value;
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
  public function getLangcode() {
    return $this->get('langcode')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLangcode($langcode) {
    $this->set('langcode', $langcode);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTranslationSetId() {
    return $this->get('translationset_id')->value;
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
    return $this->get('keywords')->value;
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
    return $this->get('tags')->value;
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
    if ($this->bundle() != IndexInterface::BUNDLE_LOCAL) {
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
    return $this->get('status')->value == IndexInterface::STATUS_LINKABLE;
  }

  /**
   * {@inheritdoc}
   */
  public function isReachable() {
    return $this->get('status')->value != IndexInterface::STATUS_NOT_REACHABLE;
  }

  /**
   * {@inheritdoc}
   */
  public function getStatus() {
    return $this->get('status')->value;
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
  public function getEntityCreatedTime() {
    return $this->get('entity_created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEntityCreatedTime($created) {
    $this->set('entity_created', $created);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityChangedTime() {
    return $this->get('entity_changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEntityChangedTime($changed) {
    $this->set('entity_changed', $changed);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getAccessibility() {
    return $this->get('accessibility')->value;
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
    $fields['id'] = FieldDefinition::create('integer')
      ->setLabel(t('Index ID'))
      ->setDescription(t('The index ID.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = FieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The index UUID.'))
      ->setReadOnly(TRUE);

    $fields['parent_uuid'] = FieldDefinition::create('string')
      ->setLabel(t('Parent UUID'))
      ->setDescription(t('The UUID of the parent index.'));

    $fields['origin'] = FieldDefinition::create('string')
      ->setLabel(t('Bundle'))
      ->setDescription(t('The origin of the index.'));

    $fields['connection_name'] = FieldDefinition::create('string')
      ->setLabel(t('Site'))
      ->setDescription(t('The originating site of the indexed entity.'));

    $fields['entity_uuid'] = FieldDefinition::create('string')
      ->setLabel(t('Entity UUID'))
      ->setDescription(t('UUID of the indexed entity.'));

    $fields['entity_type'] = FieldDefinition::create('string')
      ->setLabel(t('Entity type'))
      ->setDescription(t('Type of the indexed entity.'));

    $fields['entity_bundle'] = FieldDefinition::create('string')
      ->setLabel(t('Entity bundle'))
      ->setDescription(t('Bundle of the indexed entity.'));

    $fields['title'] = FieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('Title of the indexed entity.'));

    $fields['langcode'] = FieldDefinition::create('language')
      ->setLabel(t('Language'))
      ->setDescription(t('Language of the indexed entity.'));

    $fields['translationset_id'] = FieldDefinition::create('integer')
      ->setLabel(t('Translation set ID'))
      ->setDescription(t('ID of the translation set the indexed entity belongs to.'));

    $fields['keywords'] = FieldDefinition::create('string')
      ->setLabel(t('Keywords'))
      ->setDescription(t('Keywords for the indexed entity.'));

    $fields['tags'] = FieldDefinition::create('string')
      ->setLabel(t('Generated Keywords'))
      ->setDescription(t('Auto generated Keywords for the indexed entity.'));

    $fields['url'] = FieldDefinition::create('uri')
      ->setLabel(t('URL'))
      ->setDescription(t('The url of the indexed entity.'));

    $fields['status'] = FieldDefinition::create('integer')
      ->setLabel(t('Status'))
      ->setDescription(t('Status.'));

    $fields['accessibility'] = FieldDefinition::create('integer')
      ->setLabel(t('Accessibility'))
      ->setDescription(t('Indicates the degree the indexed entity is accessible.'));

    $fields['entity_created'] = FieldDefinition::create('integer')
      ->setLabel(t('Entity created'))
      ->setDescription(t('Creation date of the indexed entity.'));

    $fields['entity_changed'] = FieldDefinition::create('integer')
      ->setLabel(t('Entity changed'))
      ->setDescription(t('Changed date of the indexed entity.'));

    $fields['created'] = FieldDefinition::create('integer')
      ->setLabel(t('Created'))
      ->setDescription(t('Creation date of the index record.'))
      ->setReadOnly(TRUE);

    $fields['changed'] = FieldDefinition::create('integer')
      ->setLabel(t('Changed'))
      ->setDescription(t('Changed date of the index record.'))
      ->setPropertyConstraints('value', array('EntityChanged' => array()));

    $fields['is_linkable'] = FieldDefinition::create('boolean')
      ->setLabel(t('Is Linkable'))
      ->setDescription(t('Indicates if the index record can be linked.'))
      ->setReadOnly(TRUE);

    // @todo Where do those lines have to go?
//    $properties['connection_name']['options list'] = 'sharedcontent_get_connection_labels';
//    $properties['entity_bundle']['options list'] = 'sharedcontent_get_all_entity_bundle_labels';
//    $properties['language']['options list'] = 'entity_metadata_language_list';
//    $properties['status']['options list'] = 'sharedcontent_get_index_status_labels';
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
    return $fields;
  }

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
      if (in_array($key, $this->getExposedFields())
        && $this->get($key)->value != $value
      ) {
        $this->set($key, $value);
        $updated = TRUE;
      }
    }
    return $updated;
  }

  /**
   * {@inheritdoc}
   */
  public function getExposedFields() {
    return array(
      'uuid',
      'entity_uuid',
      'entity_type',
      'entity_bundle',
      'title',
      'keywords',
      'tags',
      'langcode',
      'translationset_id',
      'status',
      'url',
      'parent_uuid',
      'entity_created',
      'entity_changed',
    );
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
      'status' => IndexInterface::STATUS_VISIBLE,
      'accessibility' => IndexInterface::ACCESSIBILITY_PUBLIC,
      'origin' => IndexInterface::BUNDLE_LOCAL,
      'keywords' => '',
      'tags' => '',
      'created' => REQUEST_TIME,
    );
    parent::preCreate($storage_controller, $values);
  }
}
