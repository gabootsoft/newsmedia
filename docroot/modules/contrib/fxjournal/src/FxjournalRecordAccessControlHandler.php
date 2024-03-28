<?php

namespace Drupal\fxjournal;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the forex journal record entity type.
 */
class FxjournalRecordAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    if ($entity->bundle() != 'fxjournal_record') {
      return AccessResult::neutral();
    }

    if ($account->hasPermission('administer forex journal record')) {
      return AccessResult::allowed();
    }

    // Check if the FXJournal Record author is the current user.
    if ($account->id() != $entity->getOwner()->id()) {
      return AccessResult::forbidden();
    }

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view forex journal record');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit forex journal record');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete forex journal record');

      default:
        return AccessResult::neutral();
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, [
      'create forex journal record',
      'administer forex journal record',
    ], 'OR');
  }

}
