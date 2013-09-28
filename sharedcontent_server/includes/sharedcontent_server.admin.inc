<?php

/**
 * @file
 * Functions, forms and other stuff for Shared Content administration.
 *
 * @ingroup sharedcontent_admin
 */

/**
 * Returns a form to enable or disable reason base service access management.
 */
function sharedcontent_server_configure_variables($form, &$form_state) {
  if (module_exists('taxonomy')) {
    $form['sharedcontent_check_reason'] = array(
      '#type' => 'checkbox',
      '#default_value' => variable_get('sharedcontent_check_reason', FALSE),
      '#title' => t('Use Reasons'),
    );
  }
  else {
    $form['no_taxonomy'] = array(
      '#type' => 'item',
      '#title' => t('Taxonomy not availlable'),
      '#markup' => t('Please activate the Taxonomy module in order to use advanced service access management based on reasons.'),
    );
  }

  $themes = list_themes();
  array_walk($themes, '_sharedcontent_server_convert_to_name');

  $form['sharedcontent_overlay_theme'] = array(
    '#type' => 'select',
    '#title' => t('Overlay theme'),
    '#default_value' => variable_get('sharedcontent_overlay_theme', 'seven'),
    '#options' => $themes,
  );

  return system_settings_form($form);
}

/**
 * Convert a theme class to a human readable name.
 *
 * This is a user function targetet for array_walk().
 */
function _sharedcontent_server_convert_to_name(&$item, $key) {
  $item = $item->info['name'] . ' ' . $item->info['version'];
}
