<?php

namespace Drupal\Tests\fxjournal\Functional;

use Drupal\fxjournal\Entity\FxjournalAccount;
use Drupal\fxjournal\Entity\FxjournalSymbol;
use Drupal\fxjournal\Entity\FxjournalEvent;
use Drupal\fxjournal\Entity\FxjournalRecord;
use Drupal\Tests\RandomGeneratorTrait;

/**
 * Contains the Fxjournal entity related functionality.
 */
trait FxjournalEntityTrait {

  use RandomGeneratorTrait;

  /**
   * Generates a random.
   */
  protected function randomDecimal(int $min = 1, int $max = 10, $exp = 5) {
    $precision = 10 ** $exp;
    return mt_rand($min * $precision, $max * $precision) / $precision;
  }

  /**
   * Generates random Account related values.
   *
   * @return array
   *   The array of values
   */
  protected function generateAccountValues(): array {
    return [
      'login' => $this->randomString(16),
      'company' => $this->randomString(32),
      'currency' => $this->randomString(8),
      'leverage' => random_int(-1000, 1000),
      'type' => random_int(1, 2) == 1 ? 'Real' : 'Demo',
    ];
  }

  /**
   * Creates an Account.
   *
   * @param int $owner
   *   The owner id.
   * @param array $values
   *   The account values.
   *
   * @return \Drupal\fxjournal\FxjournalAccountInterface
   *   The newly created account.
   */
  protected function createAccount(int $owner, array $values = []) {
    if (empty($values)) {
      $values = $this->generateAccountValues();
    }

    /** @var \Drupal\fxjournal\Entity\FxjournalAccount $account */
    $account = FxjournalAccount::create();

    $account->setLogin($values['login']);
    $account->setCompany($values['company']);
    $account->setCurrency($values['currency']);
    $account->setLeverage($values['leverage']);
    $account->setType($values['type']);

    $account->setOwnerId($owner);

    $account->save();

    return $account;
  }

  /**
   * Generates random Symbol related values.
   *
   * @return array
   *   The array of values
   */
  protected function generateSymbolValues(): array {
    return [
      'title' => $this->randomString(16),
      'digits' => random_int(-10, 10),
    ];
  }

  /**
   * Creates a Symbol.
   *
   * @param int $owner
   *   The owner id.
   * @param array $values
   *   The Symbol values.
   *
   * @return \Drupal\fxjournal\FxjournalSymbolInterface
   *   The newly created Symbol.
   */
  protected function createSymbol(int $owner, array $values = []) {
    if (empty($values)) {
      $values = $this->generateSymbolValues();
    }

    /** @var \Drupal\fxjournal\Entity\FxjournalSymbol $symbol */
    $symbol = FxjournalSymbol::create();

    $symbol->setTitle($values['title']);
    $symbol->setDigits($values['digits']);

    $symbol->setOwnerId($owner);

    $symbol->save();

    return $symbol;
  }

  /**
   * Generates random Event related values.
   *
   * @return array
   *   The array of values
   */
  protected function generateEventValues(): array {
    return [
      'title' => $this->randomString(16),
      'importance' => random_int(-10, 10),
    ];
  }

  /**
   * Creates an Event.
   *
   * @param int $owner
   *   The owner id.
   * @param array $values
   *   The Event values.
   *
   * @return \Drupal\fxjournal\FxjournalEventInterface
   *   The newly created Event.
   */
  protected function createEvent(int $owner, array $values = []) {
    if (empty($values)) {
      $values = $this->generateEventValues();
    }

    /** @var \Drupal\fxjournal\Entity\FxjournalEvent $event */
    $event = FxjournalEvent::create();

    $event->setTitle($values['title']);
    $event->setImportance($values['importance']);

    $event->setOwnerId($owner);

    $event->save();

    return $event;
  }

  /**
   * Generates random Record related values.
   *
   * @param int $min
   *   The minimum value.
   * @param int $max
   *   The maximum value.
   * @param int $precision
   *   The decimal values precision.
   *
   * @return array
   *   The array of values
   */
  protected function generateRecordValues($min = -10, $max = 10, $precision = 5): array {
    return [
      'ticket' => $this->randomString(16),
      'open_datetime' => \time(),
      'type' => random_int(1, 2) == 1 ? 'Buy' : 'Sell',
      'volume' => $this->randomDecimal($min, $max, 2),
      'price_open' => $this->randomDecimal($min, $max, $precision),
      'stop_loss' => $this->randomDecimal($min, $max, $precision),
      'take_profit' => $this->randomDecimal($min, $max, $precision),
      'commission' => $this->randomDecimal($min, $max, 2),
      'close_datetime' => \time() + (60 * random_int(1, 60)),
      'price_close' => $this->randomDecimal($min, $max, $precision),
      'swap' => $this->randomDecimal($min, $max, 2),
      'profit' => $this->randomDecimal($min, $max, 2),
    ];
  }

  /**
   * Creates a Record.
   *
   * @param int $owner
   *   The owner id.
   * @param array $values
   *   The Record values.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The newly created Record.
   */
  protected function createRecord(int $owner, array $values = []) {
    if (empty($values)) {
      $values = $this->generateRecordValues();
    }

    $account = $this->createAccount($owner);
    $symbol = $this->createSymbol($owner);
    $event = $this->createEvent($owner);

    /** @var \Drupal\fxjournal\Entity\FxjournalRecord $record */
    $record = FxjournalRecord::create();

    $record->addEvent($event);
    $record->setTradeAccount($account);
    $record->setTicket($values['ticket']);
    $record->setOpenDateTime($values['open_datetime']);
    $record->setType($values['type']);
    $record->setVolume($values['volume']);
    $record->setSymbol($symbol);
    $record->setPriceOpen($values['price_open']);
    $record->setStopLoss($values['stop_loss']);
    $record->setTakeProfit($values['take_profit']);
    $record->setCommission($values['commission']);
    $record->setCloseDateTime($values['close_datetime']);
    $record->setPriceClose($values['price_close']);
    $record->setSwap($values['swap']);
    $record->setProfit($values['profit']);

    $record->setOwnerId($owner);

    $record->save();

    return $record;
  }

}
