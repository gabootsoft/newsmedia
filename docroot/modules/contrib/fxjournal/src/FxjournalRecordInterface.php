<?php

namespace Drupal\fxjournal;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\fxjournal\FxjournalAccountInterface;
use Drupal\fxjournal\FxjournalSymbolInterface;

/**
 * Provides an interface defining a forex journal record entity type.
 */
interface FxjournalRecordInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the trade symbol events.
   *
   * @return \Drupal\fxjournal\Entity\FxjournalEvent[]
   *   The trade symbol events.
   */
  public function getEvents();

  /**
   * Adds a forex journal record event.
   *
   * @param \Drupal\fxjournal\FxjournalEventInterface $event
   *   The event entity.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function addEvent(FxjournalEventInterface $event);

  /**
   * Gets the trade account entity.
   *
   * @return \Drupal\fxjournal\FxjournalAccountInterface
   *   The trade account entity.
   */
  public function getTradeAccount();

  /**
   * Sets the forex journal record trade account.
   *
   * @param \Drupal\fxjournal\FxjournalAccountInterface $account
   *   The trade account entity.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setTradeAccount(FxjournalAccountInterface $account);

  /**
   * Gets the forex journal record ticket.
   *
   * @return string
   *   Ticket of the forex journal record.
   */
  public function getTicket();

  /**
   * Sets the forex journal record ticket.
   *
   * @param string $ticket
   *   The forex journal record ticket.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setTicket($ticket);

  /**
   * Gets the forex journal record open datetime.
   *
   * @return \Drupal\Core\Datetime\DrupalDateTime
   *   The forex journal record open datetime object.
   */
  public function getOpenDateTime();

  /**
   * Sets the forex journal record open datetime.
   *
   * @param int $timestamp
   *   The timestamp.
   *
   * @return \Drupal\fxjournal\Entity\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setOpenDateTime(int $timestamp);

  /**
   * Gets the position type.
   *
   * @return string
   *   The position type.
   */
  public function getType();

  /**
   * Sets the forex journal record type.
   *
   * @param string $type
   *   The forex journal record type.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setType($type);

  /**
   * Gets the volume.
   *
   * @return float
   *   The volume.
   */
  public function getVolume();

  /**
   * Sets the forex journal record volume.
   *
   * @param float $volume
   *   The forex journal record volume.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setVolume(float $volume);

  /**
   * Gets the trade symbol entity.
   *
   * @return object
   *   The trade symbol entity.
   */
  public function getSymbol();

  /**
   * Sets the forex journal record symbol.
   *
   * @param \Drupal\fxjournal\FxjournalSymbolInterface $symbol
   *   The symbol entity.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setSymbol(FxjournalSymbolInterface $symbol);

  /**
   * Gets the forex journal record open price.
   *
   * @return float
   *   The open price.
   */
  public function getPriceOpen();

  /**
   * Sets the forex journal record open price.
   *
   * @param float $price_open
   *   The open price.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setPriceOpen(float $price_open);

  /**
   * Gets the forex journal record stop-loss price.
   *
   * @return float
   *   The stop-loss price.
   */
  public function getStopLoss();

  /**
   * Sets the forex journal record stop-loss price.
   *
   * @param float $stop_loss
   *   The stop-loss price.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setStopLoss(float $stop_loss);

  /**
   * Gets the forex journal record take-profit price.
   *
   * @return float
   *   The take-profit price.
   */
  public function getTakeProfit();

  /**
   * Sets the forex journal record take-profit price.
   *
   * @param float $take_profit
   *   The take-profit price.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setTakeProfit(float $take_profit);

  /**
   * Gets the forex journal record commission.
   *
   * @return float
   *   The commission.
   */
  public function getCommission();

  /**
   * Sets the forex journal record commission.
   *
   * @param float $commission
   *   The commission.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setCommission(float $commission);

  /**
   * Gets the forex journal record close datetime.
   *
   * @return \Drupal\Core\Datetime\DrupalDateTime
   *   The forex journal record close datetime object.
   */
  public function getCloseDateTime();

  /**
   * Sets the forex journal record close datetime.
   *
   * @param int $timestamp
   *   The timestamp.
   *
   * @return \Drupal\fxjournal\Entity\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setCloseDateTime(int $timestamp);

  /**
   * Gets the forex journal record close price.
   *
   * @return float
   *   The close price.
   */
  public function getPriceClose();

  /**
   * Sets the forex journal record close price.
   *
   * @param float $price_close
   *   The close price.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setPriceClose(float $price_close);

  /**
   * Gets the forex journal record swap.
   *
   * @return float
   *   The swap.
   */
  public function getSwap();

  /**
   * Sets the forex journal record swap.
   *
   * @param float $swap
   *   The swap.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setSwap(float $swap);

  /**
   * Gets the profit.
   *
   * @return float
   *   The profit.
   */
  public function getProfit();

  /**
   * Sets the forex journal record profit.
   *
   * @param float $profit
   *   The profit.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setProfit(float $profit);

  /**
   * Gets the forex journal record creation timestamp.
   *
   * @return int
   *   Creation timestamp of the forex journal record.
   */
  public function getCreatedTime();

  /**
   * Sets the forex journal record creation timestamp.
   *
   * @param int $timestamp
   *   The forex journal record creation timestamp.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the forex journal record owner.
   *
   * @return object
   *   Owner of the forex journal record.
   */
  public function getOwner();

  /**
   * Sets the forex journal owner.
   *
   * @param \Drupal\user\UserInterface $account
   *   The user account.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setOwner(UserInterface $account);

  /**
   * Gets the forex journal record owner id.
   *
   * @return int
   *   Owner id of the forex journal record.
   */
  public function getOwnerId();

  /**
   * Sets the forex journal record owner id.
   *
   * @param int $uid
   *   The forex journal record owner id.
   *
   * @return \Drupal\fxjournal\FxjournalRecordInterface
   *   The called forex journal record entity.
   */
  public function setOwnerId($uid);

  /**
   * Gets a field's timestamp.
   *
   * @param string $field_name
   *   The field name.
   *
   * @return mixed
   *   The timestamp or NULL on empty date.
   */
  public function getTimeStamp($field_name);

}
