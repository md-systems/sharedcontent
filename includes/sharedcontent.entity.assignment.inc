<?php

/**
 * @file
 * Contains SharedContentAssignment.
 */

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
}
