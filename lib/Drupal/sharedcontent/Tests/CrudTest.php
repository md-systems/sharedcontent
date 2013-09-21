<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Tests\CrudTest.
 */

namespace Drupal\sharedcontent\Tests;

use Drupal\simpletest\DrupalUnitTestBase;

class CrudTest extends DrupalUnitTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('user', 'system', 'field', 'sharedcontent');

  static function getInfo() {
    return array(
      'name' => 'CRUD tests',
      'description' => 'Basic crud operations for indexes and ',
      'group' => 'Shared Content',
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installSchema('sharedcontent', array('sharedcontent_index', 'sharedcontent_assignment'));
  }

  /**
   * Test CRUD functions for the index.
   */
  public function testIndex() {
    $index = entity_create('sharedcontent_index', array());
    $this->assertEqual(SAVED_NEW, $index->save(), 'Successfully saved an index.');

    $loaded_index = sharedcontent_index_load($index->id());
    $this->assertTrue($loaded_index, 'Successfully loaded index');
  }

  /**
   * Test CRUD functions for the index.
   */
  public function testAssignment() {
    $assignment = entity_create('sharedcontent_assignment', array());
    $this->assertEqual(SAVED_NEW, $assignment->save(), 'Successfully saved an assignment.');
  }
}
