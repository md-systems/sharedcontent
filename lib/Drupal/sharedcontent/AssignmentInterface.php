<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\AssignmentInterface.
 */

namespace Drupal\sharedcontent;

use Drupal\Core\Entity\EntityChangedInterface;

interface AssignmentInterface extends EntityChangedInterface {

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
}
