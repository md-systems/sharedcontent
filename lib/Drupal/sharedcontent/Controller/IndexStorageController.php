<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Controller\IndexStorageController.
 */

namespace Drupal\sharedcontent\Controller;

use Drupal\Core\Entity\DatabaseStorageControllerNG;

/**
 * Metadata controller for the sharedcontent index entity.
 */
class IndexStorageController extends DatabaseStorageControllerNG {

  /**
   * [@inheritdoc}
   */
  public function baseFieldDefinitions() {
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
      'read-only' => TRUE,
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
}
