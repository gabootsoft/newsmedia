<?php

namespace Drupal\fxjournal;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\fxjournal\FxjournalEntityListBuilderTrait;

/**
 * Provides a list controller for the forex journal record entity type.
 */
class FxjournalRecordListBuilder extends EntityListBuilder {

  use FxjournalEntityListBuilderTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    if ($this->ownDashboard() == FALSE) {
      $header['uid'] = $this->t('Author');
    }
    $header['account_currency'] = $this->t('Account Currency');
    $header['open_datetime'] = $this->t('Open Datetime');
    $header['ticket'] = $this->t('Ticket');
    $header['type'] = $this->t('Type');
    $header['volume'] = $this->t('Volume');
    $header['symbol'] = $this->t('Symbol');
    $header['price_open'] = $this->t('Open Price');
    $header['stop_loss'] = $this->t('Stop Loss');
    $header['take_profit'] = $this->t('Take Profit');
    $header['close_datetime'] = $this->t('Close Datetime');
    $header['price_close'] = $this->t('Exit Price');
    $header['profit'] = $this->t('Profit');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\fxjournal\Entity\FxjournalRecord $entity */

    if ($this->ownDashboard() == FALSE) {
      $owner = $entity->getOwner();

      $row['uid']['data'] = [
        '#theme' => 'username',
        '#account' => $owner,
      ];
    }
    $row['account_currency'] = $entity->getTradeAccount()->getCurrency();
    $row['open_datetime'] = $entity->getOpenDateTime();
    $row['ticket'] = $entity->toLink();
    $row['type'] = $entity->getType();
    $row['volume'] = $entity->getVolume();
    $row['symbol'] = $entity->getSymbol()->getTitle();
    $row['price_open'] = $entity->getPriceOpen();
    $row['stop_loss'] = $entity->getStopLoss();
    $row['take_profit'] = $entity->getTakeProfit();
    $row['close_datetime'] = $entity->getCloseDateTime();
    $row['price_close'] = $entity->getPriceClose();
    $row['profit'] = $entity->getProfit();

    return $row + parent::buildRow($entity);
  }

}
