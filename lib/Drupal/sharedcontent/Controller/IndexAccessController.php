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
class IndexAccessController extends EntityAccessController {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {
    // Grant access if user has full access.
    if ($account->hasPermission('access endpoint full')) {
      return TRUE;
    }

    // Skip early if user does not have any access.
    if (!$account->hasPermission('access endpoint restricted')) {
      return FALSE;
    }

    // Grant access if reason checking is disabled.
    if (!\Drupal::config('sharedcontent.settings')->get('check_reason')) {
      return TRUE;
    }
    // Grant access if no specific entity is provided.
    elseif (!$entity) {
      return TRUE;
    }
    // Check reasons.
    else {
      // Skip early if there are no reasons to match.
      if (empty($entity->field_sharedcontent_reason)) {
        return FALSE;
      }

      // Use global user if no account was provided.
      if (!$account) {
        global $user;
        $account = $user;
      }

      // Reload user to get a record including the attached fields.
      $account = entity_load('user', $account->id());

      // No valid user detected.
      if (!$account) {
        return FALSE;
      }

      // Skip early if there are no reasons to match.
      if (empty($account->field_sharedcontent_reason)) {
        return FALSE;
      }

      $user_reasons = array();
      foreach ($account->field_sharedcontent_reason as $field_item) {
        $user_reasons[] = $field_item->target_id;
      }

      foreach ($entity->field_sharedcontent_reason as $field_item) {
        if (in_array($field_item->target_id, $user_reasons)) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return $account->hasPermission('access endpoint full') | $account->hasPermission('access endpoint restricted');
  }
}
