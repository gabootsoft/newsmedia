<?php

namespace Drupal\fxjournal\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Returns responses for Forex Journal routes.
 */
class FxjournalDashboardController extends ControllerBase {

  /**
   * The entity type manager instance.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $typeManager;

  /**
   * The controller constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManager $type_manager) {
    $this->typeManager = $type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Checks access for a specific request.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   Run access checks for this account.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $user) {
    return AccessResult::allowedIf(
      $user->hasPermission('view forex journal record') &&
      $this->currentUser()->id() == $user->id()
    );
  }

  /**
   * Generates the dashboard render content.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The user object.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return array
   *   The dashboard render content.
   */
  public function dashboard(AccountInterface $user, Request $request) {
    return [
      'content' => [
        $this->typeManager->getListBuilder('fxjournal_record')->render(),
        $this->typeManager->getListBuilder('fxjournal_event')->render(),
        $this->typeManager->getListBuilder('fxjournal_account')->render(),
        $this->typeManager->getListBuilder('fxjournal_symbol')->render(),
      ],
    ];
  }

}
