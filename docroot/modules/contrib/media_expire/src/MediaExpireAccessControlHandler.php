<?php

namespace Drupal\media_expire;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\media\MediaAccessControlHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the access control handler for the media entity type.
 *
 * @see \Drupal\media\Entity\Media
 */
class MediaExpireAccessControlHandler extends MediaAccessControlHandler implements EntityHandlerInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a MediaExpireAccessControlHandler object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($entity_type);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\Core\Access\AccessResult $accessResult */
    $accessResult = parent::checkAccess($entity, $operation, $account);

    switch ($operation) {
      case 'view':
        if (!$accessResult->isAllowed()) {
          $bundle = $this->entityTypeManager
            ->getStorage('media_type')
            ->load($entity->bundle());

          return AccessResult::allowedIf(
              $account->hasPermission('view media') &&
              $bundle->getThirdPartySetting('media_expire', 'enable_expiring') &&
              $bundle->getThirdPartySetting('media_expire', 'fallback_media')
          );
        }
    }

    return $accessResult;
  }

}
