<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Controller\IndexAccessController.
 */

namespace Drupal\sharedcontent\Controller;

use Drupal\Core\Entity\EntityAccessController;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access controller for the node entity type.
 */
class AssignmentAccessController extends EntityAccessController {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {
    return $this->checkCreateAccess($account, array());
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return $account->hasPermission('access endpoint full') || $account->hasPermission('access endpoint restricted');
  }
}
