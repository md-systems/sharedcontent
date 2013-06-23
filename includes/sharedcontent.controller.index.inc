<?php

/**
 * @file
 * Contains SharedContentIndexController.
 */

/**
 * Controller for sharedcontent_index entity.
 */
class SharedContentIndexController extends SharedContentBaseController {

  /**
   * Override EntityAPIController.
   */
  public function create(array $values = array()) {
    $values += array(
      'status' => SHAREDCONTENT_INDEX_STATUS_VISIBLE,
      'accessibility' => SHAREDCONTENT_INDEX_ACCESSIBILITY_PUBLIC,
      'origin' => SHAREDCONTENT_INDEX_BUNDLE_LOCAL,
      'connection_name' => SHAREDCONTENT_LOCAL_CONNECTION_NAME,
    );
    return parent::create($values);
  }
}
