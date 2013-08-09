<?php

/**
 * @file
 * Contains SharedContentAssignment.
 */

namespace Drupal\sharedcontent\Plugin\Core\Entity;

use Drupal\Core\Entity\Entity;
use Drupal\Core\Entity\EntityStorageControllerInterface;

/**
 * Shared Content Assignment
 *
 * Describes the linkage between two shared entities.
 */
class SharedContentAssignment extends Entity {

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
      'status' => SHAREDCONTENT_ASSIGNMENT_ACTIVE,
      'url' => url(NULL, array('absolute' => TRUE)),
      'origin' => SHAREDCONTENT_INDEX_BUNDLE_LOCAL,
      'created' => REQUEST_TIME,
    );
    parent::preCreate($storage_controller, $values);
  }
}
