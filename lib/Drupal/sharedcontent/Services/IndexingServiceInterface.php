<?php

/**
 * @file
 * Contains Drupal\sharedcontent\Services\IndexingServiceInterface.
 */

namespace Drupal\sharedcontent\Services;

use Drupal\Core\Entity\EntityInterface;

interface IndexingServiceInterface {

  /**
   * Index an entity.
   *
   * Creates or updates an index record for the given entity.
   *
   * @param EntityInterface $entity
   *   The entity to be indexed.
   */
  public function index(EntityInterface $entity);

  /**
   * Index a deleted entity.
   *
   * Index records are never deleted. When an indexed entity gets deleted the
   * index status is set to 'not reachable'.
   *
   * @param EntityInterface $entity
   *   The deleted entity.
   */
  public function delete(EntityInterface $entity);
}
