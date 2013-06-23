<?php

/**
 * @file
 * Contains SharedContentAssignmentController.
 */

/**
 * Controller for sharedcontent_assignment entity.
 */
class SharedContentAssignmentController extends SharedContentBaseController {

  /**
   * Override EntityAPIController.
   */
  public function create(array $values = array()) {
    $values += array(
      'status' => SHAREDCONTENT_ASSIGNMENT_ACTIVE,
      'url' => url(NULL, array('absolute' => TRUE)),
      'origin' => SHAREDCONTENT_INDEX_BUNDLE_LOCAL,
    );
    return parent::create($values);
  }

  /**
   * Override EntityAPIController.
   */
  public function save($entity) {
    if (isset($entity->source)) {
      $source = sharedcontent_index_load_by_uuid($entity->source);
      $entity->source_id = $source->id;
    }

    if (isset($entity->target) && $target = sharedcontent_index_load_by_uuid($entity->target)) {
      $entity->target_id = $target->id;
    }

    // Make sure we have a valid uuid for this entity.
    // For testing purposed we allow invalid uuids.
    if (!$this->uuidIsValid($entity->uuid) && !variable_get('sharedcontent_test', FALSE)) {
      $entity->uuid = uuidGenerate();
    }
    $entity->changed = REQUEST_TIME;
    return parent::save($entity);
  }
}
