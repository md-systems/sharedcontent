<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Plugin\Core\Entity\Assignment.
 */

namespace Drupal\sharedcontent\Entity;

use Drupal\Core\Entity\EntityNG;
use Drupal\Core\Entity\EntityStorageControllerInterface;
use Drupal\Core\Entity\Annotation\EntityType;
use Drupal\Core\Annotation\Translation;
use Drupal\sharedcontent\AssignmentInterface;
use Drupal\sharedcontent\IndexInterface;

//$entities['sharedcontent_assignment'] = array(
//
//  'access callback' => 'sharedcontent_assignment_access', // @todo AccessController
//  'metadata controller class' => 'SharedContentAssignmentMetadataController', // @todo EntityNG
//  'views controller class' => 'SharedContentAssignmentViewsController',
//);

/**
 * Defines the Shared Content Assignment entity class
 *
 * Describes the linkage between two shared entities.
 *
 * @EntityType(
 *   id = "sharedcontent_assignment",
 *   label = @Translation("Shared Content Assignment"),
 *   bundle_label = @Translation("Origin"),
 *   module = "sharedcontent",
 *   controllers = {
 *     "storage" = "Drupal\sharedcontent\Controller\AssignmentStorageController"
 *   },
 *   base_table = "sharedcontent_assignment",
 *   fieldable = FALSE,
 *   translatable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "origin",
 *     "uuid" = "uuid"
 *   },
 *   bundle_keys = {
 *     "bundle" = "origin"
 *   }
 * )
 */
class Assignment extends EntityNG implements AssignmentInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions($entity_type) {
    $properties['id'] = array(
      'label' => t('Assignment ID'),
      'description' => t('The assignments ID.'),
      'type' => 'integer_field',
      'read-only' => TRUE,
    );
    $properties['uuid'] = array(
      'label' => t('UUID'),
      'description' => t('The assignments UUID.'),
      'type' => 'uuid_field',
      'read-only' => TRUE,
    );
    $properties['source'] = array(
      'label' => t('Source'),
      'description' => t('The uuid of the source index record.'),
      'type' => 'string_field',
    );
    $properties['target'] = array(
      'label' => t('target'),
      'description' => t('The uuid of the target index record.'),
      'type' => 'string_field',
    );
    $properties['origin'] = array(
      'label' => t('Bundle'),
      'description' => t('The origin of the assignment.'),
      'type' => 'string_field',
    );
    $properties['status'] = array(
      'label' => t('Status'),
      'description' => t('The status linkage status.'),
      'type' => 'integer_field',
    );
    $properties['url'] = array(
      'label' => t('Source System'),
      'description' => t('The base url of the system reporting this assignment.'),
      'type' => 'uri_field',
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
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageControllerInterface $storage_controller, array &$values) {
    $values += array(
      'status' => AssignmentInterface::STATUS_ACTIVE,
      'url' => url(NULL, array('absolute' => TRUE)),
      'origin' => AssignmentInterface::BUNDLE_LOCAL,
      'created' => REQUEST_TIME,
    );
    parent::preCreate($storage_controller, $values);
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageControllerInterface $storage_controller) {
    parent::preSave($storage_controller);

    $this->get('changed')->setValue(REQUEST_TIME);
  }

  /**
   * Getter for the source index.
   *
   * Gets the entity object of the source index record.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The source index entity.
   */
  public function getSource() {
    return entity_load_by_uuid('sharedcontent_index', $this->getSourceUuid());
  }

  /**
   * Setter for the source index.
   *
   * Sets the entity object of the source index record.
   *
   * @param IndexInterface $origin
   *   The source index entity.
   */
  public function setSource(IndexInterface $origin) {
    $this->setSourceUuid($origin->uuid());
  }

  /**
   * Getter for the uuid of the source index.
   *
   * @return string
   *   The uuid of the source index.
   */
  public function getSourceUuid() {
    return $this->get('source')->value;
  }

  /**
   * Setter for the uuid of the source index.
   *
   * @param string $uuid
   *   The uuid of the new source index record.
   */
  public function setSourceUuid($uuid) {
    $this->set('source', $uuid);
  }

  /**
   * Getter for the target index.
   *
   * Gets the entity object of the target index record.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The target index entity.
   */
  public function getTarget() {
    return entity_load_by_uuid('sharedcontent_index', $this->getTargetUuid());
  }

  /**
   * Setter for the target index.
   *
   * Sets the entity object of the source target record.
   *
   * @param IndexInterface $target
   *   The target index entity.
   */
  public function setTarget(IndexInterface $target) {
    $this->setTargetUuid($target->uuid());
  }

  /**
   * Getter for the uuid of the target index.
   *
   * @return string
   *   The uuid of the target index.
   */
  public function getTargetUuid() {
    return $this->get('target')->value;
  }

  /**
   * Setter for the uuid of the target index.
   *
   * @param string $uuid
   *   The uuid of the new target index record.
   */
  public function setTargetUuid($uuid) {
    $this->set('target', $uuid);
  }

  /**
   * Sets the bundle.
   *
   * @param string $origin
   *   The new origin of this assignment record.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setBundle($origin) {
    $this->set('origin', $origin);
  }

  /**
   * Getter for the assignment status.
   *
   * @return int
   *   Number indicating if the assignment is active or deleted.
   *
   * @see \Drupal\sharedcontent\AssignemtInterface::STATUS_ACTIVE
   * @see \Drupal\sharedcontent\AssignemtInterface::STATUS_DELETED
   */
  public function getStatus() {
    return $this->get('status')->value;
  }

  /**
   * Setter for the assignment status.
   *
   * @param int $status
   *   Number indicating if the assignment is active or deleted.
   *
   * @see \Drupal\sharedcontent\AssignemtInterface::STATUS_ACTIVE
   * @see \Drupal\sharedcontent\AssignemtInterface::STATUS_DELETED
   */
  public function setStatus($status) {
    $this->set('status', $status);
  }

  /**
   * Getter for the url of the originating system.
   *
   * @return string
   *   The base url of the system this assignment was created on.
   */
  public function getUrl() {
    return $this->get('url')->value;
  }

  /**
   * Setter for the url of the originating system.
   *
   * @param string $url
   *   The base url of the system this assignment was created on.
   */
  public function setUrl($url) {
    $this->set('url', $url);
  }

  /**
   * Returns the records creation date.
   *
   * @return int
   *   Timestamp of the creation date.
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * Returns the timestamp of the last entity change.
   *
   * @return int
   *   The timestamp of the last entity save operation.
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }
}
