<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Tests\IndexingTest.
 */

namespace Drupal\sharedcontent\Tests;

use Drupal\simpletest\DrupalUnitTestBase;

class IndexingTest extends DrupalUnitTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array(
    'user',
    'system',
    'field',
    'node',
    'sharedcontent',
  );

  static function getInfo() {
    return array(
      'name' => 'Indexing tests',
      'description' => 'Test the indexing of content.',
      'group' => 'Shared Content',
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installSchema('sharedcontent', array(
      'sharedcontent_index',
      'sharedcontent_assignment',
    ));

    $this->installSchema('node', array(
      'node',
      'node_field_data',
      'node_field_revision',
    ));
  }

  /**
   * Test CRUD functions for the index.
   */
  public function testNewIndexOnNodeCreation() {
    $node = entity_create('node', array(
      'type' => 'indexed',
    ));
    $node->save();

    $index = sharedcontent_index_load_by_entity($node);
    $this->assertTrue($index, 'Found index record for created node.');
  }
}
