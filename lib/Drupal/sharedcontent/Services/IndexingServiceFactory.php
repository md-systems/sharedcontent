<?php

/**
 * @file
 * Contains Drupal\sharedcontent\Services\IndexingServiceFactory.
 */

namespace Drupal\sharedcontent\Services;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class IndexingServiceFactory
 *
 * Factory to get the indexing service for an entity.
 *
 * @package Drupal\sharedcontent\Services
 */
class IndexingServiceFactory extends ContainerAware {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructs an Indexing instance.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactory $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Gets the indexing service for given entity.
   *
   * @param EntityInterface $entity
   *   The entity to get the indexing service for.
   *
   * @return \Drupal\sharedcontent\Services\IndexingServiceInterface
   *   The indexing service to be used for the entity.
   */
  public function get(EntityInterface $entity) {
    return $this->container->get($this->getServiceId($entity));
  }

  /**
   * Gets the indexing service id.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to get teh indexing service id for.
   *
   * @return string
   *   The id of the indexing service to be used.
   */
  protected function getServiceId(EntityInterface $entity) {
    $key = $this->configKey($entity->entityType(), $entity->bundle());
    $service_name = $this->configFactory->get('sharedcontent.indexables')->get($key);
    $service_id = 'sharedcontent.indexing.';
    $service_id .= $service_name ? $service_name : 'null';
    return $service_id;
  }

  /**
   * Helper for creating a settings key.
   *
   * @param string $entity_type
   *   An entity type.
   * @param string $bundle
   *   An entity bundle
   *
   * @return string
   *   The resulting settings key.
   */
  protected function configKey($entity_type, $bundle) {
    $entity_type = preg_replace('/[^0-9a-zA-Z_]/', "_", $entity_type);
    $bundle = preg_replace('/[^0-9a-zA-Z_]/', "_", $bundle);
    return $entity_type . '.' . $bundle . '.service';
  }
}
