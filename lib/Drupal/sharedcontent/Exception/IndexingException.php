<?php
/**
 * @file
 * Contains Drupal\sharedcontent\Exception\IndexingException.
 */

namespace Drupal\sharedcontent\Exception;

/**
 * No parent index record
 *
 * This error is thrown then trying to index a chapter where the parent
 * index record does not exists.
 *
 * @see sharedcontent_chapters_sharedcontent_index_presave().
 */
define('SHAREDCONTENT_ERROR_NO_PARENT_INDEX', 7);

/**
 * Indexing errors
 *
 * This exception is thrown during errors in the indexing process.
 *
 * @see SHAREDCONTENT_ERROR_NO_PARENT_INDEX
 */
class IndexingException extends \Exception {

  public $data = array();

  /**
   * Constructor.
   *
   * @param int $code
   *   The error code for this exception.
   * @param array $data
   *   (optional) Array of data collected during the service interaction.
   * @param Exception $previous
   *   (optional) The previous exception used for the exception chaining.
   */
  public function __construct($code, array $data = NULL, Exception $previous = NULL) {
    $this->data = $data;
    switch ($code) {
      case SHAREDCONTENT_ERROR_NO_PARENT_INDEX:
        $message = 'Parent index record does not exists for chapter.';
        break;

      default:
        $message = 'Failed to update index.';
    }
    parent::__construct($message, $code, $previous);
  }
}
