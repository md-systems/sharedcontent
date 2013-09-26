<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\EntityExposedFieldsInterface.
 */

namespace Drupal\sharedcontent;

interface EntityExposedFieldsInterface {

  /**
   * Returns a list of exposed fields.
   *
   * @return array
   *   List of exposed fields for this entity.
   */
  public function getExposedFields();
}
