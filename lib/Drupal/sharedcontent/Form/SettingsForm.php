<?php

/**
 * @file
 * Contains Drupal\sharedcontent\Form\SettingsForm.
 */

namespace Drupal\sharedcontent\Form;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\Context\ContextInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure site information settings for this site.
 */
class SettingsForm extends ConfigFormBase {
  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a SiteInformationForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Config\Context\ContextInterface $context
   *   The configuration context used for this configuration object.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(ConfigFactory $config_factory, ContextInterface $context, ModuleHandlerInterface $module_handler) {
    parent::__construct($config_factory, $context);

    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('config.context.free'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'sharedcontent.settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $config = $this->config('sharedcontent.settings');

    if ($this->moduleHandler->moduleExists('sharedcontent_server')) {
      if ($this->moduleHandler->moduleExists('taxonomy')) {
        $form['check_reason'] = array(
          '#type' => 'checkbox',
          '#default_value' => $config->get('check_reason'),
          '#title' => t('Use Reasons'),
        );
      }
      else {
        $form['no_taxonomy'] = array(
          '#type' => 'item',
          '#title' => t('Taxonomy not available'),
          '#markup' => t('Please activate the Taxonomy module in order to use advanced service access management based on reasons.'),
        );
      }

      $themes = list_themes();
      array_walk($themes, function(&$item, &$key) {
        $item = $item->info['name'] . ' ' . $item->info['version'];
      });

      $form['overlay_theme'] = array(
        '#type' => 'select',
        '#title' => t('Overlay theme'),
        '#default_value' => $config->get('overlay_theme'),
        '#options' => $themes,
      );
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    $config = $this->config('sharedcontent.settings');

    form_state_values_clean($form_state);

    foreach ($form_state['values'] as $config_key => $config_value) {
      $config->set($config_key, $config_value);
    }

    $config->save();

    parent::submitForm($form, $form_state);
  }
}
