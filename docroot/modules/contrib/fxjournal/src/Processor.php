<?php

namespace Drupal\fxjournal;

use Drupal\user\UserData;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Processor service.
 */
class Processor implements ProcessorInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The user data instance.
   *
   * @var \Drupal\user\UserData
   */
  protected $userData;

  /**
   * Constructs a Processor object.
   *
   * @param \Drupal\user\UserData $user_data
   *   The user data instance.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager instance.
   */
  public function __construct(UserData $user_data, EntityTypeManagerInterface $entity_type_manager) {
    $this->userData = $user_data;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getAccounts(int $user_id, bool $select_options = TRUE): array {
    $out = [];

    $accounts = $this->entityTypeManager
      ->getStorage('fxjournal_account')
      ->loadByProperties(['uid' => $user_id]);

    if ($select_options !== TRUE) {
      return $accounts;
    }

    /** @var \Drupal\fxjournal_account\Entity\FxjournalAccount $account */
    foreach ($accounts as $account) {
      $out[$account->id()] = implode(' | ', [
        $account->getType(),
        $account->getLogin(),
        $account->getCompany(),
        $account->getCurrency(),
      ]);
    }

    return $out;

  }

  /**
   * {@inheritdoc}
   */
  public function getSymbols(int $user_id, bool $select_options = TRUE): array {
    $out = [];

    $symbols = $this->entityTypeManager
      ->getStorage('fxjournal_symbol')
      ->loadByProperties(['uid' => $user_id]);

    if ($select_options !== TRUE) {
      return $symbols;
    }

    /** @var \Drupal\fxjournal_symbol\Entity\FxjournalSymbol $symbol */
    foreach ($symbols as $symbol) {
      $out[$symbol->id()] = implode(' | ', [
        $symbol->getTitle(),
        $symbol->getDigits(),
      ]);
    }

    return $out;

  }

  /**
   * {@inheritdoc}
   */
  public function getEvents(int $user_id, bool $select_options = TRUE): array {
    $out = [];

    $events = $this->entityTypeManager
      ->getStorage('fxjournal_event')
      ->loadByProperties(['uid' => $user_id]);

    if ($select_options !== TRUE) {
      return $events;
    }

    /** @var \Drupal\fxjournal_event\Entity\FxjournalEvent $event */
    foreach ($events as $event) {
      $out[$event->id()] = implode(' | ', [
        $event->getTitle(),
        $event->getImportance(),
      ]);
    }

    return $out;

  }

}
