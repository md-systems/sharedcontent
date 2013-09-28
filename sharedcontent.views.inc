<?php

/**
 * @file
 * Provide views data and handlers for sharedcontent.module.
 *
 * @ingroup views_module_handlers
 */

use Drupal\sharedcontent\Entity\Index;

/**
 * Implements hook_views_data().
 */
function sharedcontent_views_data() {
  $data['sharedcontent_index']['table']['group']  = t('Shared Content Index');

  $data['sharedcontent_index']['table']['base'] = array(
    'field' => 'id',
    'title' => t('Shared Content Index'),
    'help' => t('Index with metadata about a content.'),
    'access query tag' => 'sharedcontent_access',
  );
  $data['sharedcontent_index']['table']['entity type'] = 'sharedcontent_index';

  $definitions = Index::baseFieldDefinitions(NULL);

  $data['sharedcontent_index']['id'] = array(
    'title' => $definitions['id']['label'],
    'help' => $definitions['id']['description'],
    'field' => array('id' => 'numeric'),
    'argument' => array('id' => 'numeric'),
    'filter' => array('id' => 'numeric'),
    'sort' => array('id' => 'numeric'),
  );

  $data['sharedcontent_index']['uuid'] = array(
    'title' => $definitions['uuid']['label'],
    'help' => $definitions['uuid']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['parent_uuid'] = array(
    'title' => $definitions['parent_uuid']['label'],
    'help' => $definitions['parent_uuid']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['origin'] = array(
    'title' => $definitions['origin']['label'],
    'help' => $definitions['origin']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['connection_name'] = array(
    'title' => $definitions['connection_name']['label'],
    'help' => $definitions['connection_name']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['entity_uuid'] = array(
    'title' => $definitions['entity_uuid']['label'],
    'help' => $definitions['entity_uuid']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['entity_type'] = array(
    'title' => $definitions['entity_type']['label'],
    'help' => $definitions['entity_type']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['entity_bundle'] = array(
    'title' => $definitions['entity_bundle']['label'],
    'help' => $definitions['entity_bundle']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['title'] = array(
    'title' => $definitions['title']['label'],
    'help' => $definitions['title']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['langcode'] = array(
    'title' => $definitions['langcode']['label'],
    'help' => $definitions['langcode']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['translationset_id'] = array(
    'title' => $definitions['translationset_id']['label'],
    'help' => $definitions['translationset_id']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['keywords'] = array(
    'title' => $definitions['keywords']['label'],
    'help' => $definitions['keywords']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['tags'] = array(
    'title' => $definitions['tags']['label'],
    'help' => $definitions['tags']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['url'] = array(
    'title' => $definitions['url']['label'],
    'help' => $definitions['url']['description'],
    'field' => array('id' => 'url'),
    'argument' => array('id' => 'url'),
    'filter' => array('id' => 'url'),
    'sort' => array('id' => 'url'),
  );

  $data['sharedcontent_index']['tags'] = array(
    'title' => $definitions['tags']['label'],
    'help' => $definitions['tags']['description'],
    'field' => array('id' => 'standard'),
    'argument' => array('id' => 'standard'),
    'filter' => array('id' => 'standard'),
    'sort' => array('id' => 'standard'),
  );

  $data['sharedcontent_index']['status'] = array(
    'title' => $definitions['status']['label'],
    'help' => $definitions['status']['description'],
    'field' => array('id' => 'numeric'),
    'argument' => array('id' => 'numeric'),
    'filter' => array('id' => 'numeric'),
    'sort' => array('id' => 'numeric'),
  );

  $data['sharedcontent_index']['entity_created'] = array(
    'title' => $definitions['entity_created']['label'],
    'help' => $definitions['entity_created']['description'],
    'field' => array('id' => 'date'),
    'argument' => array('id' => 'date'),
    'filter' => array('id' => 'date'),
    'sort' => array('id' => 'date'),
  );

  $data['sharedcontent_index']['entity_changed'] = array(
    'title' => $definitions['entity_changed']['label'],
    'help' => $definitions['entity_changed']['description'],
    'field' => array('id' => 'date'),
    'argument' => array('id' => 'date'),
    'filter' => array('id' => 'date'),
    'sort' => array('id' => 'date'),
  );

  $data['sharedcontent_index']['created'] = array(
    'title' => $definitions['created']['label'],
    'help' => $definitions['created']['description'],
    'field' => array('id' => 'date'),
    'argument' => array('id' => 'date'),
    'filter' => array('id' => 'date'),
    'sort' => array('id' => 'date'),
  );

  $data['sharedcontent_index']['changed'] = array(
    'title' => $definitions['changed']['label'],
    'help' => $definitions['changed']['description'],
    'field' => array('id' => 'date'),
    'argument' => array('id' => 'date'),
    'filter' => array('id' => 'date'),
    'sort' => array('id' => 'date'),
  );

  return $data;
}
