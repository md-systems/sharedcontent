<?php
/**
 * @file
 * Contains \Drupal\sharedcontent\Exception\CountIndexException.
 */

namespace Drupal\sharedcontent\Exception;

/**
 * Count index exception.
 *
 * This exception occurs when counting the indexes fails.
 */
class CountIndexException extends \Exception {

  /**
   * Constructor.
   *
   * @param string $connection_name
   *   The name of the connection the exception occurred.
   * @param int $time_start
   *   The start time of the time period.
   * @param int $time_end
   *   The end time of the time period.
   * @param \Exception $previous
   *   (optional) The previous exception used for the exception chaining.
   */
  public function __construct($connection_name, $time_start, $time_end, \Exception $previous = NULL) {
    $message = format_string('Could not count index for time frame !start – !end using connection !name.', array(
      '!name' => $connection_name,
      '!start' => $time_start,
      '!end' => $time_end,
    ));
    parent::__construct($message, 0, $previous);
  }
}
