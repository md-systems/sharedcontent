<?php
/**
 * @file
 * Views plugins used by Shared Content.
 */

/**
 * Shared Content node type filter extension class.
 */
class SharedContentCapableContentTypes extends SearchApiViewsHandlerFilterBoolean {

  // Do not handle operator.
  var $no_operator = TRUE;

  /**
   * Override query().
   */
  function query() {
    $this->ensure_my_table();
    if ($this->value) {
      $sc_node_types = $this->get_node_types_with_shared_content_field();
      // $filter will be NULL if there were errors in the query.
      if (!empty($sc_node_types) && $filter = $this->query->createFilter('OR')) {
        foreach ($sc_node_types as $sc_node_type) {
          $filter->condition($this->real_field, $sc_node_type, $this->options['group']);
        }
        $this->query->filter($filter, $this->options['group']);
      }
      else {
        $this->query->condition($this->real_field, FALSE, $this->options['group']);
      }
      $this->query->condition('entity_type', 'node', $this->options['group']);
    }
  }

  /**
   * Helper function to collect all node types having a shared content field.
   *
   * @return array
   *
   * @todo Make this work also for non nodes.
   */
  function get_node_types_with_shared_content_field() {
    static $sc_node_types = NULL;

    if ($sc_node_types === NULL) {
      $sc_field_names = sharedcontent_client_get_all_shared_content_field_names();
      $node_info = entity_get_info('node');

      $sc_node_types = array();
      foreach (array_keys($node_info['bundles']) as $node_bundle) {
        foreach ($sc_field_names as $sc_field_name) {
          if (field_info_instance('node', $sc_field_name, $node_bundle)) {
            $sc_node_types[] = $node_bundle;
          }
        }
      }
    }

    return $sc_node_types;
  }
}

/**
 * Shared Content node type filter extension class.
 */
class SharedContentQueuedRemoteContent extends SearchApiViewsHandlerFilterOptions {

  // Only allow single selection.
  var $value_form_type = 'radios';

  // Do not handle operator.
  var $no_operator = TRUE;

  /**
   * Override operator_options().
   */
  public function operator_options() {
    return array();
  }

  /**
   * Override query().
   */
  function query() {
    // Fetch the ids of the flagged entities.
    $flagged_entity_ids = array();
    if (!empty($this->value)) {
      foreach (flag_get_flagged_content($this->value) as $flag) {
        $flagged_entity_ids[] = flag_get_content_id($flag->fcid);
      }
    }

    // $filter will be NULL if there were errors in the query.
    if (!empty($flagged_entity_ids) && $filter = $this->query->createFilter('OR')) {
      foreach ($flagged_entity_ids as $id) {
        $filter->condition($this->real_field, $id, $this->operator);
      }
      $this->query->filter($filter, $this->options['group']);
    }
    else {
      // Create a query that will not return any results.
      $this->query->condition($this->real_field, -1, $this->operator, $this->options['group']);
    }
  }
}

/**
 * Shared Content assigned filter extension class.
 */
class SharedContentIndexAssigned extends SearchApiViewsHandlerFilterBoolean {

  // Do not handle operator.
  var $no_operator = TRUE;

  /**
   * Override query().
   */
  function query() {
    if ($this->value) {
      $no_assignments = TRUE;
      if ($id = sharedcontent_ui_get_dashboard_active_local_content_id()) {
        $index = entity_load_single('sharedcontent_index', $id);
        $values = db_select('sharedcontent_assignment', 'a')
          ->fields('a', array('source'))
          ->condition('target', $index ? $index->uuid : FALSE)
          ->condition('status', 0)
          ->execute()->fetchCol();
        if (!empty($values)) {
          $filter = $this->query->createFilter('OR');
          // $filter will be NULL if there were errors in the query.
          if ($filter) {
            foreach ($values as $value) {
              $filter->condition($this->real_field, $value);
            }
            $this->query->filter($filter, $this->options['group']);
            $no_assignments = FALSE;
          }
        }
      }
      if ($no_assignments) {
        $this->query->condition($this->real_field, FALSE, $this->operator, $this->options['group']);
      }
    }
  }
}

/**
 * Shared Content assigned filter extension class.
 */
class SharedContentIndexSelf extends SearchApiViewsHandlerFilterBoolean {

  // Do not handle operator.
  var $no_operator = TRUE;

  /**
   * Override query().
   */
  function query() {
    if ($id = sharedcontent_ui_get_dashboard_active_local_content_id()) {
      $index = entity_load_single('sharedcontent_index', $id);
      if (isset($index)) {
        $operator = $this->value ? '=' : '<>';
        $this->query->condition($this->real_field, $index->uuid, $operator, $this->options['group']);
      }
    }
    elseif ($this->value) {
      $this->query->condition($this->real_field, NULL, '=', $this->options['group']);
    }
  }
}

/**
 * Default argument plugin to extract current selected node and to use it's tags
 * for fulltext search.
 */
class SharedContentIndexTags extends views_plugin_argument_default {

  /**
   * @todo doc
   */
  function get_argument() {
    $id = sharedcontent_ui_get_dashboard_active_local_content_id();

    if ($id) {
      // Try to get tags from local index, if selected node is indexed.
      $index = entity_load_single('sharedcontent_index', $id);
      if ($index) {
        $keywords = $index->title;
        $keywords .= ' ' . $index->keywords;
        $keywords .= ' ' . $index->tags;
        return $keywords;
      }
    }

    return '';
  }

}

/**
 * Current selected index record.
 *
 * Default argument plugin to extract the id of the current selected
 * index record.
 */
class SharedContentIndexSelectedRecord extends views_plugin_argument_default {

  function get_argument() {
    $id = sharedcontent_ui_get_dashboard_active_local_content_id();
    if ($id) {
        return $id;
    }
    return NULL;
  }
}
