<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Tests\AccessTest.
 */

namespace Drupal\sharedcontent\Tests;

use Drupal\Core\Entity\EntityInterface;
use Drupal\system\Tests\Entity\EntityUnitTestBase;

class SecurityTest extends EntityUnitTestBase {

  static function getInfo() {
    return array(
      'name' => 'Sequrity tests',
      'description' => 'Test entity access and other security concernes.',
      'group' => 'Shared Content',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->enableModules(array('sharedcontent'));
    $this->installSchema('sharedcontent', array(
      'sharedcontent_index',
      'sharedcontent_assignment',
    ));
  }

  /**
   * Test index access.
   */
  public function testIndexAccess() {
    $this->enableModules(array('taxonomy'));
    $this->installSchema('taxonomy', array(
      'taxonomy_term_data',
      'taxonomy_term_hierarchy',
      'taxonomy_index',
    ));
    $this->installConfig(array('sharedcontent'));

    // Given two reason terms.
    $term_a = entity_create('taxonomy_term', array(
      'name' => $this->randomName(),
      'vid' => 'sharedcontent_reason',
    ));
    $term_a->save();

    $term_b = entity_create('taxonomy_term', array(
      'name' => $this->randomName(),
      'vid' => 'sharedcontent_reason',
    ));
    $term_b->save();

    $reason_a = array('field_sharedcontent_reason' => $term_a);
    $reason_b = array('field_sharedcontent_reason' => $term_b);
    $operations_all = array('view', 'create', 'update', 'delete');
    $operations_create = array('create');
    $operations_other = array('view', 'update', 'delete');
    $access_none = array();
    $access_full = array('access endpoint full');
    $access_restricted = array('access endpoint restricted');

    $index_a = entity_create('sharedcontent_index', $reason_a);
    $index_a->save();

    $index_b = entity_create('sharedcontent_index', $reason_b);
    $index_b->save();

    \Drupal::config('sharedcontent.settings')->set('check_reason', FALSE);

    $this->assertEntityAccess($index_a, $access_none, array(), $operations_all);
    $this->assertEntityAccess($index_b, $access_none, array(), $operations_all);
    $this->assertEntityAccess($index_a, $access_full, $operations_all, array());
    $this->assertEntityAccess($index_b, $access_full, $operations_all, array());
    $this->assertEntityAccess($index_a, $access_restricted, $operations_all, array());
    $this->assertEntityAccess($index_b, $access_restricted, $operations_all, array());

    \Drupal::config('sharedcontent.settings')->set('check_reason', TRUE);

    $this->assertEntityAccess($index_a, $access_none, array(), $operations_all);
    $this->assertEntityAccess($index_b, $access_none, array(), $operations_all);
    $this->assertEntityAccess($index_a, $access_full, $operations_all, array());
    $this->assertEntityAccess($index_b, $access_full, $operations_all, array());
    $this->assertEntityAccess($index_a, $access_restricted, $operations_create, $operations_other);
    $this->assertEntityAccess($index_b, $access_restricted, $operations_create, $operations_other);

    $this->assertEntityAccess($index_a, $access_none, array(), $operations_all, $reason_a);
    $this->assertEntityAccess($index_b, $access_none, array(), $operations_all, $reason_a);
    $this->assertEntityAccess($index_a, $access_full, $operations_all, array(), $reason_a);
    $this->assertEntityAccess($index_b, $access_full, $operations_all, array(), $reason_a);
    $this->assertEntityAccess($index_a, $access_restricted, $operations_all, array(), $reason_a);
    $this->assertEntityAccess($index_b, $access_restricted, $operations_create, $operations_other, $reason_a);
  }

  /**
   * Test assignment access.
   */
  public function testAssignmentAccess() {
    $assignment = entity_create('sharedcontent_assignment', array());
    $assignment->save();

    $operations_all = array('view', 'create', 'update', 'delete');
    $access_none = array();
    $access_full = array('access endpoint full');
    $access_restricted = array('access endpoint restricted');

    $this->assertEntityAccess($assignment, $access_none, $access_none, $operations_all);
    $this->assertEntityAccess($assignment, $access_restricted, $operations_all, array());
    $this->assertEntityAccess($assignment, $access_full, $operations_all, array());
  }

  /**
   * @var int
   */
  protected $nextUid = 2;

  /**
   * {@inheritdoc}
   */
  protected function createUser($values = array(), $permissions = array()) {
    $uid = $this->nextUid++;
    $values += array('uid' => $uid);
    return parent::createUser($values, $permissions);
  }

  /**
   * Message template for access granted assertion.
   */
  const MSG_GRANTED = '%op access granted with permissions %permission for entity %entity with bundle %bundle.';

  /**
   * Message template for access prohibited assertion.
   */
  const MSG_PROHIBITED = '%op access prohibited with permissions %permission for entity %entity with bundle %bundle.';

  /**
   * Asserts access to the assignment entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to test access for.
   * @param array $permissions
   *   (optional) Array of permissions the user must have.
   * @param array $granted
   *   (optional) Operations to the entity that should be granted.
   * @param array $prohibited
   *   (optional) Operations to the entity that should be prohibited.
   * @param array $user_values
   *   (optional) The values used to create the
   */
  public function assertEntityAccess(EntityInterface $entity, array $permissions = array(), array $granted = array(), array $prohibited = array(), array $user_values = array()) {
    $account = $this->createUser($user_values, $permissions);

    $options = array(
      '%permission' => empty($permissions) ? 'none' : implode(', ', $permissions),
      '%entity' => $entity->entityType(),
      '%bundle' => $entity->bundle(),
    );

    foreach ($granted as $op) {
      $options['%op'] = ucfirst($op);
      $this->assertTrue($entity->access($op, $account), format_string(SecurityTest::MSG_GRANTED, $options));
    }
    foreach ($prohibited as $op) {
      $options['%op'] = ucfirst($op);
      $this->assertFalse($entity->access($op, $account), format_string(SecurityTest::MSG_PROHIBITED, $options));
    }
  }
}
