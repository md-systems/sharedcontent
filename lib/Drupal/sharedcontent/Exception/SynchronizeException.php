<?php
/**
 * @file
 * Contains \Drupal\sharedcontent\Exception\SynchronizeException.
 */

namespace Drupal\sharedcontent\Exception;

/**
 * Synchronization error
 *
 * Indicates errors that occurred during synchronization.
 * Use the encapsulated exception to figure out what has gone wrong and
 * the arguments to see the associated data.
 *
 * Use drush sc-sync or the resynchronization button to fix inconsistent
 * data that might have emerged from this error.
 */
class SynchronizeException extends \Exception {

  /**
   * List of debugging data.
   * @var array
   */
  protected $data;

  /**
   * Constructor.
   *
   * @param string $connection_name
   *   The name of the connection the exception occurred.
   * @param array $data
   *   (optional) Array of data collected during the service interaction.
   * @param Exception $previous
   *   (optional) The previous exception used for the exception chaining.
   */
  public function __construct($connection_name, array $data = array(), Exception $previous = NULL) {
    $this->data = $data;
    $message = format_string('Failed to synchronize index with !name', array(
      '!name' => $connection_name,
    ));
    parent::__construct($message, 0, $previous);
  }

  /**
   * Gets the arguments.
   *
   * @return array
   *   The arguments array.
   */
  public function getData() {
    return $this->data;
  }
}
