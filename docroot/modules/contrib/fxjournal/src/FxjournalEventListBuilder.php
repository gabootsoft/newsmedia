<?php

namespace Drupal\fxjournal;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\fxjournal\FxjournalEntityListBuilderTrait;

/**
 * Provides a list controller for the forex journal event entity type.
 */
class FxjournalEventListBuilder extends EntityListBuilder {

  use FxjournalEntityListBuilderTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    if (!$this->ownDashboard()) {
      $header['uid'] = $this->t('Author');
    }
    $header['title'] = $this->t('Title');
    $header['importance'] = $this->t('Importance');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\fxjournal\FxjournalEventInterface $entity */

    if (!$this->ownDashboard()) {
      $owner = $entity->getOwner();

      $row['uid']['data'] = [
        '#theme' => 'username',
        '#account' => $owner,
      ];
    }

    $row['title'] = $entity->toLink();
    $row['importance'] = $entity->getImportance();
    return $row + parent::buildRow($entity);
  }

}
