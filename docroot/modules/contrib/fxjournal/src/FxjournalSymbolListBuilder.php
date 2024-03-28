<?php

namespace Drupal\fxjournal;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\fxjournal\FxjournalEntityListBuilderTrait;

/**
 * Provides a list controller for the forex journal symbol entity type.
 */
class FxjournalSymbolListBuilder extends EntityListBuilder {

  use FxjournalEntityListBuilderTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    if (!$this->ownDashboard()) {
      $header['uid'] = $this->t('Author');
    }
    $header['title'] = $this->t('Title');
    $header['digits'] = $this->t('Digits');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\fxjournal\FxjournalSymbolInterface $entity */

    if (!$this->ownDashboard()) {
      $owner = $entity->getOwner();

      $row['uid']['data'] = [
        '#theme' => 'username',
        '#account' => $owner,
      ];
    }
    $row['title'] = $entity->toLink();
    $row['digits'] = $entity->getDigits();
    return $row + parent::buildRow($entity);
  }

}
