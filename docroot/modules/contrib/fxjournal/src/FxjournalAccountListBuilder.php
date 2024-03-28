<?php

namespace Drupal\fxjournal;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\fxjournal\FxjournalEntityListBuilderTrait;

/**
 * Provides a list controller for the forex journal account entity type.
 */
class FxjournalAccountListBuilder extends EntityListBuilder {

  use FxjournalEntityListBuilderTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    if (!$this->ownDashboard()) {
      $header['uid'] = $this->t('Author');
    }
    $header['login'] = $this->t('Login');
    $header['company'] = $this->t('Company');
    $header['currency'] = $this->t('Currency');
    $header['leverage'] = $this->t('Leverage');
    $header['type'] = $this->t('Type');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\fxjournal\FxjournalAccountInterface $entity */

    if (!$this->ownDashboard()) {
      $owner = $entity->getOwner();

      $row['uid']['data'] = [
        '#theme' => 'username',
        '#account' => $owner,
      ];
    }

    $row['login'] = $entity->toLink();
    $row['company'] = $entity->getCompany();
    $row['currency'] = $entity->getCurrency();
    $row['leverage'] = $this->t('1:@leverage', ['@leverage' => $entity->getLeverage()]);
    $row['type'] = $entity->getType();
    return $row + parent::buildRow($entity);
  }

}
