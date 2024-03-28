<?php

namespace Drupal\fxjournal;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the forex journal symbol entity type.
 */
class FxjournalSymbolAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    if ($entity->bundle() != 'fxjournal_symbol') {
      return AccessResult::neutral();
    }

    if ($account->hasPermission('administer forex journal symbol')) {
      return AccessResult::allowed();
    }

    // Check if the FXJournal Symbol author is the current user.
    if ($account->id() != $entity->getOwner()->id()) {
      return AccessResult::forbidden();
    }

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view forex journal symbol');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit forex journal symbol');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete forex journal symbol');

      default:
        return AccessResult::neutral();
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, ['create forex journal symbol', 'administer forex journal symbol'], 'OR');
  }

}
