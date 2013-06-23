<?php

/**
 * @file
 * Contains SharedContentAssignmentMetadataController.
 */

/**
 * Metadata controller for the sharedcontent assignment entity.
 */
class SharedContentAssignmentMetadataController extends EntityDefaultMetadataController {

  /**
   * Overrides EntityDefaultMetadataController::entityPropertyInfo().
   */
  public function entityPropertyInfo() {
    $info = parent::entityPropertyInfo();
    $properties = & $info[$this->type]['properties'];

    // id.
    $properties['id']['setter callback'] = 'entity_property_verbatim_set';

    // Origin - the bundle.
    $properties['origin']['setter callback'] = 'entity_property_verbatim_set';
    $properties['origin']['label'] = t('Bundle');

    // source_id.
    $properties['source_id']['setter callback'] = 'entity_property_verbatim_set';
    $properties['source_id']['type'] = 'sharedcontent_index';

    // source.
    $properties['source']['setter callback'] = 'entity_property_verbatim_set';

    // target_id.
    $properties['target_id']['setter callback'] = 'entity_property_verbatim_set';
    $properties['target_id']['type'] = 'sharedcontent_index';

    // target.
    $properties['target']['setter callback'] = 'entity_property_verbatim_set';

    // status.
    $properties['status']['setter callback'] = 'entity_property_verbatim_set';
    $properties['status']['options list'] = 'sharedcontent_get_assignment_status_labels';

    // url.
    $properties['url']['setter callback'] = 'entity_property_verbatim_set';

    // created.
    $properties['created']['setter callback'] = 'entity_property_verbatim_set';
    $properties['created']['type'] = 'date';

    // changed.
    $properties['changed']['setter callback'] = 'entity_property_verbatim_set';
    $properties['changed']['type'] = 'date';

    return $info;
  }
}
