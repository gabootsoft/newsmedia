<?php

namespace Drupal\fxjournal;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control for the forex journal account entity.
 */
class FxjournalAccountAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    if ($entity->bundle() != 'fxjournal_account') {
      return AccessResult::neutral();
    }

    if ($account->hasPermission('administer forex journal account')) {
      return AccessResult::allowed();
    }

    // Check if the FXJournal  Account author is the current user.
    if ($account->id() != $entity->getOwner()->id()) {
      return AccessResult::forbidden();
    }

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view forex journal account');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit forex journal account');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete forex journal account');

      default:
        return AccessResult::neutral();
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, ['create forex journal account', 'administer forex journal account'], 'OR');
  }

}
