<?php

/**
 * @file
 * Contains Drupal\sharedcontent\Services\DefaultIndexing.
 */

namespace Drupal\sharedcontent\Services;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\sharedcontent\IndexInterface;

/**
 * Class DefaultIndexing
 *
 * Default indexing implementation. The indexing takes place immediately.
 *
 * @package Drupal\sharedcontent\Services
 */
class DefaultIndexing extends IndexingServiceBase implements IndexingServiceInterface {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Sets the module handler.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function setModuleHandler(ModuleHandlerInterface $module_handler) {
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public function index(EntityInterface $entity) {
    $index = $this->load($entity);
    if (!$index) {
      $index = $this->entityManager->getStorageController('sharedcontent_index')->create(array(
        'entity_uuid' => $entity->uuid(),
        'entity_type' => $entity->getEntityTypeId(),
        'entity_bundle' => $entity->bundle(),
        'origin' => IndexInterface::BUNDLE_LOCAL,
      ));
    }
    $this->update($index, $entity);
    $this->invokeAlter($index, $index->isNew() ? 'create' : 'update');
    $index->save();
  }

  /**
   * {@inheritdoc}
   */
  public function delete(EntityInterface $entity) {
    if ($this->exists($entity)) {
      $index = $this->load($entity);
      // Make sure we got the latest data.
      $this->update($index, $entity);
      $index->setStatus(IndexInterface::STATUS_NOT_REACHABLE);
      $this->invokeAlter($index, 'delete');
      $index->save();
    }
  }

  /**
   * Invokes altering of an index record.
   *
   * @param IndexInterface $index
   *   The index entity to be altered.
   * @param string $op
   *   The operation triggered this altering.
   */
  protected function invokeAlter(IndexInterface $index, $op) {
    // Trigger alter hook and rules event allowing other parties to alter
    // the index to their likings.
    $this->moduleHandler->alter('sharedcontent_index', $index, $op);
    // @todo Port rules invocation once rules got ported.
    if (function_exists('rules_invoke_event')) {
      $event = 'sharedcontent_index_is_being_' . $op . 'd';
      rules_invoke_event($event, $index);
    }
  }
}