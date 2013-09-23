<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\AssignmentInterface.
 */

namespace Drupal\sharedcontent;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

interface AssignmentInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * The assignment record was created locally.
   */
  const BUNDLE_LOCAL = 'local';

  /**
   * The assignment record was received during a synchronization process.
   */
  const BUNDLE_REMOTE = 'remote';

  /**
   * The assignment record was received by a push from a client.
   */
  const BUNDLE_SHADOW = 'shadow';

  /**
   * Status value for the sharedcontent_assignment entity
   *
   * States that the assignment is active.
   */
  const STATUS_ACTIVE = 0;

  /**
   * Status value for the sharedcontent_assignment entity
   *
   * States that the assignment is deleted and no longer exists.
   */
  const STATUS_DELETED = 1;

  /**
   * Getter for the source index.
   *
   * Gets the entity object of the source index record.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The source index entity.
   */
  public function getSource();


  /**
   * Setter for the source index.
   *
   * Sets the entity object of the source index record.
   *
   * @param IndexInterface $origin
   *   The source index entity.
   */
  public function setSource(IndexInterface $origin);

  /**
   * Getter for the uuid of the source index.
   *
   * @return string
   *   The uuid of the source index.
   */
  public function getSourceUuid();

  /**
   * Setter for the uuid of the source index.
   *
   * @param string $uuid
   *   The uuid of the new source index record.
   */
  public function setSourceUuid($uuid);

  /**
   * Getter for the target index.
   *
   * Gets the entity object of the target index record.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The target index entity.
   */
  public function getTarget();

  /**
   * Setter for the target index.
   *
   * Sets the entity object of the source target record.
   *
   * @param IndexInterface $target
   *   The target index entity.
   */
  public function setTarget(IndexInterface $target);

  /**
   * Getter for the uuid of the target index.
   *
   * @return string
   *   The uuid of the target index.
   */
  public function getTargetUuid();

  /**
   * Setter for the uuid of the target index.
   *
   * @param string $uuid
   *   The uuid of the new target index record.
   */
  public function setTargetUuid($uuid);

  /**
   * Sets the bundle.
   *
   * @param string $origin
   *   The new origin of this assignment record.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setBundle($origin);

  /**
   * Getter for the assignment status.
   *
   * @return int
   *   Number indicating if the assignment is active or deleted.
   *
   * @see \Drupal\sharedcontent\AssignemtInterface::STATUS_ACTIVE
   * @see \Drupal\sharedcontent\AssignemtInterface::STATUS_DELETED
   */
  public function getStatus();

  /**
   * Setter for the assignment status.
   *
   * @param int $status
   *   Number indicating if the assignment is active or deleted.
   *
   * @see \Drupal\sharedcontent\AssignemtInterface::STATUS_ACTIVE
   * @see \Drupal\sharedcontent\AssignemtInterface::STATUS_DELETED
   */
  public function setStatus($status);

  /**
   * Getter for the url of the originating system.
   *
   * @return string
   *   The base url of the system this assignment was created on.
   */
  public function getUrl();

  /**
   * Setter for the url of the originating system.
   *
   * @param string $url
   *   The base url of the system this assignment was created on.
   */
  public function setUrl($url);

  /**
   * Returns the records creation date.
   *
   * @return int
   *   Timestamp of the creation date.
   */
  public function getCreatedTime();
}
