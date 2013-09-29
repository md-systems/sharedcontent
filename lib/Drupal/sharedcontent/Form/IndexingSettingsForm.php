<?php

/**
 * @file
 * Contains Drupal\sharedcontent\Form\IndexingSettingsForm.
 */

namespace Drupal\sharedcontent\Form;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\Context\ContextInterface;
use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\field\FieldInfo;
use Drupal\sharedcontent\Services\IndexingServiceFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure the indexing behaviour for Shared Content.
 */
class IndexingSettingsForm extends ConfigFormBase {

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
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;
  /**
   * The field info service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $fieldInfo;

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
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\field\FieldInfo $field_info
   *   The field info service.
   */
  public function __construct(ConfigFactory $config_factory, ContextInterface $context, EntityManager $entity_manager, IndexingServiceFactory $indexing_service_factory, ModuleHandlerInterface $module_handler, FieldInfo $field_info) {
    parent::__construct($config_factory, $context);

    $this->entityManager = $entity_manager;
    $this->indexingServiceFactory = $indexing_service_factory;
    $this->moduleHandler = $module_handler;
    $this->fieldInfo = $field_info;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('config.context.free'),
      $container->get('entity.manager'),
      $container->get('sharedcontent.indexing'),
      $container->get('module_handler'),
      $container->get('field.info')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'sharedcontent.indexing_settings';
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
      // Exclude entities that do no make sense to be shared.
      if (empty($entity_info['controllers']['render'])
        || !in_array('Drupal\\Core\\Entity\\ContentEntityInterface', class_implements($entity_info['class']))) {
        continue;
      }

      foreach ($this->entityManager->getBundleInfo($entity_type) as $bundle => $bundle_info) {
        $grouping = $entity_type . '-' . $bundle;
        $label = $bundle_info['label'];
        if ($entity_info['label'] != $bundle_info['label']) {
          $label = format_string('@entity: @bundle', array(
            '@entity' => $entity_info['label'],
            '@bundle' => $bundle_info['label'],
          ));
        }
        $form[$grouping] = array(
          '#type' => 'details',
          '#title' => $label,
        );

        $service_name = $this->indexingServiceFactory->getServiceName($entity_type, $bundle);
        $service_config_key = $this->indexingServiceFactory->configKey($entity_type, $bundle);
        $form[$grouping][$this->sanitizeKey($service_config_key)] = array(
          '#type' => 'radios',
          '#title' => $this->t('Indexing service'),
          '#description' => $this->t('Service to be used for indexing this bundle.'),
          '#default_value' => $service_name,
          '#options' => $indexers,
        );

        if ($this->moduleHandler->moduleExists('taxonomy')) {
          $keyword_fields = array();
          $fields = $this->entityManager->getFieldDefinitions($entity_type, $bundle);
          foreach ($fields as $field_name => $field_info) {
            if ($field_info['type'] == 'field_item:taxonomy_term_reference') {
              $instance = $this->fieldInfo->getInstance($entity_type, $bundle, $field_name);
              $keyword_fields[$field_name] = $instance->label();
            }
          }

          if (!empty($keyword_fields)) {
            $keyword_fields_config_key = $this->indexingServiceFactory->configKey($entity_type, $bundle, 'keywords');
            $keyword_fields_config_value = $this->config('sharedcontent.indexing')->get($keyword_fields_config_key);
            $form[$grouping][$this->sanitizeKey($keyword_fields_config_key)] = array(
              '#type' => 'checkboxes',
              '#title' => $this->t('Keyword fields'),
              '#description' => $this->t('Taxonomy fields used to build keywords from on indexing.'),
              '#options' => $keyword_fields,
              '#default_value' => $keyword_fields_config_value ? $keyword_fields_config_value : array(),
            );
          }
        }
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
    $config = $this->config('sharedcontent.indexing');

    form_state_values_clean($form_state);

    foreach ($form_state['values'] as $config_key => $config_value) {
      $config_key = str_replace('-', '.', $config_key);
      if (is_array($config_value)) {
        $config_value = array_keys(array_filter($config_value));
      }
      $config->set($config_key, $config_value);
    }

    $config->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Sanitize config key for usage in forms.
   *
   * Dots are replaced with dashes.
   *
   * @param string $key
   *   The key.
   *
   * @return string
   *   The sanitized key.
   */
  protected function sanitizeKey($key) {
    return str_replace('.', '-', $key);
  }
}
