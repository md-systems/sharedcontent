<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Services.
 */

namespace Drupal\sharedcontent\Services;

use Drupal\Core\Entity\EntityInterface;

/**
 * Class NullIndexing
 *
 * Null implementation of the indexing service.
 *
 * @package Drupal\sharedcontent\Services
 */
class NullIndexing implements IndexingServiceInterface {

  /**
   * {@inheritdoc}
   */
  public function index(EntityInterface $entity) {
    // Intentionally left blank.
  }

  /**
   * {@inheritdoc}
   */
  public function delete(EntityInterface $entity) {
    // Intentionally left blank.
  }
}
