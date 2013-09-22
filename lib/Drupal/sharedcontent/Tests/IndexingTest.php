<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Tests\IndexingTest.
 */

namespace Drupal\sharedcontent\Tests;

use Drupal\Core\Language\Language;
use Drupal\sharedcontent\Entity\Index;
use Drupal\sharedcontent\IndexInterface;
use Drupal\simpletest\DrupalUnitTestBase;

class IndexingTest extends DrupalUnitTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('user', 'system', 'field', 'sharedcontent');

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
  }

  /**
   * Test indexing for nodes.
   */
  public function testIndexingNode() {
    $this->enableModules(array('node'));
    $this->installSchema('node', array(
      'node',
      'node_field_data',
      'node_field_revision',
    ));

    // Given bundle 'indexed' of entity 'node' is 'not enabled' for indexing.
    Index::setIndexable('node', 'indexable', FALSE);

    // When I create a new entity of type 'node' with bundle 'indexed'.
    $node = entity_create('node', array(
      'title' => 'Not indexed node',
      'type' => 'indexed',
    ));
    $node->save();

    // Then no index record was created.
    $this->assertFalse(sharedcontent_index_exists($node), 'No index record was created.');

    // Given bundle 'indexed' of entity 'node' is 'enabled' for indexing.
    Index::setIndexableByEntity($node, TRUE);

    // When I create a new entity of type 'node' with bundle 'indexed'.
    $node = entity_create('node', array(
      'title' => 'Indexed node',
      'type' => 'indexed',
    ));
    $node->save();

    // Then a new index record was created.
    $this->assertTrue(sharedcontent_index_exists($node), 'Found index record for created node.');

    // And the created index matches the values from the indexed 'node'.
    $index = sharedcontent_index_load_by_entity($node);
    $this->assertEqual($index->getConnectionName(), NULL, 'The connection name is empty.');
    $this->assertEqual($index->getChangedTime(), REQUEST_TIME, 'The index record was last changed within this request.');
    $this->assertEqual($index->getCreatedTime(), REQUEST_TIME, 'The index record was created within this request.');
    $this->assertEqual($index->getEntityChangedTime(), $node->getChangedTime(), 'The changed time matches.');
    $this->assertEqual($index->getEntityCreatedTime(), $node->getCreatedTime(), 'The created time matches.');
    $this->assertEqual($index->getEntityType(), 'node', 'The entity type matches.');
    $this->assertEqual($index->getEntityBundle(), 'indexed', 'The entity bundle matches.');
    $this->assertEqual($index->getEntityUuid(), $node->uuid(), 'The indexed id matches.');
    $this->assertEqual($index->getKeywords(), NULL, 'The keywords are empty.');
    $this->assertEqual($index->getLangcode(), Language::LANGCODE_DEFAULT, 'The language is undefined.');
    $this->assertEqual($index->getParentUuid(), NULL, 'The index has no parent.');
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_VISIBLE, 'The index record has status visible.');
    $this->assertEqual($index->getTags(), NULL, 'The tags are empty.');
    $this->assertEqual($index->getTitle(), 'Indexed node', 'The title matches.');
    $this->assertEqual($index->getTranslationSetId(), '', 'The translation set id is empty.');
    $node_uri = $node->uri();
    $this->assertTrue(preg_match("|{$node_uri['path']}$|", $index->getUrl()), 'The translation set id is empty.');
  }
}
