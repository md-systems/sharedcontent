<?php

/**
 * @file
 * Contains Drupal\sharedcontent\Form\IndexedEntitiesForm.
 */

namespace Drupal\sharedcontent\Form;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\Context\ContextInterface;
use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\sharedcontent\Services\IndexingServiceFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure site information settings for this site.
 */
class IndexedEntitiesForm extends ConfigFormBase {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManager
   */
  protected $entityManager;

  /**
   * The indexing service factory.
   *
   * @var \Drupal\sharedcontent\Services\IndexingServiceFactory
   */
  protected $indexingServiceFactory;

  /**
   * Constructs a SiteInformationForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Config\Context\ContextInterface $context
   *   The configuration context used for this configuration object.
   * @param \Drupal\Core\Entity\EntityManager $entity_manager
   *   The entity manager.
   * @param \Drupal\sharedcontent\Services\IndexingServiceFactory $indexing_service_factory
   *   The indexing service factory.
   */
  public function __construct(ConfigFactory $config_factory, ContextInterface $context, EntityManager $entity_manager, IndexingServiceFactory $indexing_service_factory) {
    parent::__construct($config_factory, $context);

    $this->entityManager = $entity_manager;
    $this->indexingServiceFactory = $indexing_service_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('config.context.free'),
      $container->get('entity.manager'),
      $container->get('sharedcontent.indexing')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'system.site_information_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {

    $indexers = array(
      'null' => 'No Indexing',
      'default' => 'Indexed',
      'queued' => 'Delayed indexing',
    );

    foreach ($this->entityManager->getDefinitions() as $entity_type => $entity_info) {
      if ($entity_type == 'sharedcontent_index') {
        continue;
      }

      $form[$entity_type] = array(
        '#type' => 'fieldset',
        '#title' => $entity_info['label'],
      );

      foreach ($this->entityManager->getBundleInfo($entity_type) as $bundle => $bundle_info) {
        $service_name = $this->indexingServiceFactory->getServiceName($entity_type, $bundle);
        $config_key = $this->indexingServiceFactory->configKey($entity_type, $bundle);
        $config_key = str_replace('.', '-', $config_key);
        $form[$entity_type][$config_key] = array(
          '#type' => 'radios',
          '#title' => $bundle_info['label'],
          '#default_value' => $service_name,
          '#options' => $indexers,
        );
      }
    }

    $form['#attached']['css'] = array(
      drupal_get_path('module', 'sharedcontent') . '/css/sharedcontent_config_forms.css',
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    $config = $this->configFactory->get('sharedcontent.indexables');

    form_state_values_clean($form_state);

    foreach ($form_state['values'] as $config_key => $service_name) {
      $config_key = str_replace('-', '.', $config_key);
      $config->set($config_key, $service_name);
    }

    $config->save();

    parent::submitForm($form, $form_state);
  }
}
