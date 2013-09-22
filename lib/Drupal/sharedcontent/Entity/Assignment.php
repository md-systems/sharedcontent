<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Plugin\Core\Entity\Assignment.
 */

namespace Drupal\sharedcontent\Entity;

use Drupal\Core\Entity\Entity;
use Drupal\Core\Entity\EntityStorageControllerInterface;
use Drupal\Core\Entity\Annotation\EntityType;
use Drupal\Core\Annotation\Translation;
use Drupal\sharedcontent\AssignmentInterface;

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
 *     "storage" = "Drupal\Core\Entity\DatabaseStorageController"
 *   },
 *   base_table = "sharedcontent_assignment",
 *   fieldable = FALSE,
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
class Assignment extends Entity implements AssignmentInterface {

  /**
   * Entity id..
   */
  public $id;

  /**
   * Id of source content.
   */
  public $source_id;

  /**
   * uuid of source content..
   */
  public $source;

  /**
   * Id of target content.
   */
  public $target_id;

  /**
   * uuid of target content..
   */
  public $target;

  /**
   * The record origin.
   *
   * This defines the bundle of the entity with "local" val as default.
   */
  public $origin;

  /**
   * Linking status..
   */
  public $status;

  /**
   * Base URL of the system that reported this linking..
   */
  public $url;

  /**
   * Timestamp of the time the linking was created the last..
   */
  public $created;

  /**
   * Timestamp of the time the linking was changed the last..
   */
  public $changed;

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->changed;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageControllerInterface $storage_controller) {
    if (isset($this->source)) {
      $source = sharedcontent_index_load_by_uuid($this->source);
      $this->source_id = $source->id;
    }

    if (isset($this->target) && $target = sharedcontent_index_load_by_uuid($this->target)) {
      $this->target_id = $target->id;
    }

    $this->created = REQUEST_TIME;
    parent::preSave($storage_controller);
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
}
