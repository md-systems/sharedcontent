<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Plugin\Core\Entity\Assignment.
 */

namespace Drupal\sharedcontent\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageControllerInterface;
use Drupal\Core\Entity\Annotation\EntityType;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Field\FieldDefinition;
use Drupal\sharedcontent\AssignmentInterface;
use Drupal\sharedcontent\IndexInterface;

/**
 * 'views controller class' => 'SharedContentAssignmentViewsController'
 */

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
 *     "storage" = "Drupal\Core\Entity\FieldableDatabaseStorageController",
 *     "access" = "Drupal\sharedcontent\Controller\AssignmentAccessController"
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
class Assignment extends ContentEntityBase implements AssignmentInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions($entity_type) {
    $fields['id'] = FieldDefinition::create('integer')
      ->setLabel(t('Index ID'))
      ->setDescription(t('The index ID.'))
      ->setReadOnly(TRUE);

    $fields['id'] = FieldDefinition::create('integer')
      ->setLabel(t('Assignment ID'))
      ->setDescription(t('The assignments ID.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = FieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The assignments UUID.'))
      ->setReadOnly(TRUE);

    $fields['source'] = FieldDefinition::create('string')
      ->setLabel(t('Source'))
      ->setDescription(t('The uuid of the source index record.'));

    $fields['target'] = FieldDefinition::create('string')
      ->setLabel(t('target'))
      ->setDescription(t('The uuid of the target index record.'));

    $fields['origin'] = FieldDefinition::create('string')
      ->setLabel(t('Bundle'))
      ->setDescription(t('The origin of the assignment.'));

    $fields['status'] = FieldDefinition::create('integer')
      ->setLabel(t('Status'))
      ->setDescription(t('The status linkage status.'));

    $fields['url'] = FieldDefinition::create('uri')
      ->setLabel(t('Source System'))
      ->setDescription(t('The base url of the system reporting this assignment.'));

    $fields['created'] = FieldDefinition::create('integer')
      ->setLabel(t('Created'))
      ->setDescription(t('Creation date of the index record.'))
      ->setReadOnly(TRUE);

    $fields['changed'] = FieldDefinition::create('integer')
      ->setLabel(t('Changed'))
      ->setDescription(t('Changed date of the index record.'))
      ->setPropertyConstraints('value', array('EntityChanged' => array()));

    return $fields;
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
   * {@inheritdoc}
   */
  public function getExposedFields() {
    return array(
      'uuid',
      'source',
      'target',
      'status',
      'url',
      'created',
      'changed',
    );
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
