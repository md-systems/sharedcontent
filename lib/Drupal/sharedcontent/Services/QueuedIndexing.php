<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Services\QueuedIndexing.
 */

namespace Drupal\sharedcontent\Services;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Queue\QueueFactory;
use Drupal\sharedcontent\Exception\IndexingException;
use Drupal\sharedcontent\Services\DefaultIndexing;

/**
 * Class QueuedIndexing
 *
 * Indexing implementation that uses queueing.
 *
 * @package Drupal\sharedcontent\Services\QueuedIndexing
 */
class QueuedIndexing extends DefaultIndexing implements IndexingServiceInterface {

  const OPERATION_INDEX = 'index';
  const OPERATION_DELETE = 'delete';

  /**
   * The queue.
   *
   * @var \Drupal\Core\Queue\QueueInterface
   */
  protected $queue;

  /**
   * Setter for the queue.
   *
   * @param QueueFactory $queue_factory
   *   The queue factory.
   * @param string $queue_name
   *   The name of the queue.
   */
  public function setQueue(QueueFactory $queue_factory, $queue_name) {
    $this->queue = $queue_factory->get($queue_name, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function index(EntityInterface $entity) {
    $this->queue->createItem($this->getItem($entity, QueuedIndexing::OPERATION_INDEX));
  }

  /**
   * {@inheritdoc}
   */
  public function delete(EntityInterface $entity) {
    $this->queue->createItem($this->getItem($entity, QueuedIndexing::OPERATION_DELETE));
  }

  /**
   * Creates a queue item.
   *
   * @param EntityInterface $entity
   *   The entity to be queued.
   * @param string $op
   *   The operation to be queued.
   *
   * @return array
   *   The resulting queue item.
   */
  protected function getItem(EntityInterface $entity, $op) {
    return array(
      'entity_id' => $entity->id(),
      'entity_type' => $entity->entityType(),
      'op' => $op,
      'count' => 0,
    );
  }

  /**
   * Index queued entities.
   *
   * @param array $item
   *   Array containing the queued data.
   *     - type: the entity type.
   *     - entity_id: the entity id.
   */
  public function dequeue(array $item) {
    $controller = $this->entityManager->getStorageController($item['entity_type']);
    $entity = $controller->load($item['entity_id']);
    if ($entity) {
      try {
        switch ($item['op']) {
          case QueuedIndexing::OPERATION_INDEX:
            parent::index($entity);
            break;

          case QueuedIndexing::OPERATION_DELETE:
            parent::delete($entity);
            break;

          default:
            // @todo Log.
        }
      }
      catch (IndexingException $e) {
        if ($e->getCode() == SHAREDCONTENT_ERROR_NO_PARENT_INDEX
          && $item['count'] < 1
        ) {
          // We do not have control about the order the items get indexed.
          // If we do come here the first time we do a reschedule the first
          // time expecting the parent index record to be generated later
          // during this cron run.
          $item['count'] = 1;
          $this->queue->createItem($item);
        }
        else {
          // @todo Log.
        }
      }
    }
  }
}
