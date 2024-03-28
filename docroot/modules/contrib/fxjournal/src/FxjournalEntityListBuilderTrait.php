<?php

namespace Drupal\fxjournal;

use Drupal\Core\Entity\EntityInterface;

/**
 * Contains the fxjournal list builders' related functionality.
 */
trait FxjournalEntityListBuilderTrait {

  /**
   * Checks if the user is on own dashboard.
   */
  protected function ownDashboard() {
    return \Drupal::service('current_route_match')->getRouteName() === 'fxjournal.user.dashboard';
  }

  /**
   * Sets the filter-by-uid in the query.
   *
   * @return object
   *   The query object.
   */
  protected function fxJournalEntityQuery() {
    $query = $this->getStorage()->getQuery();

    /** @var \Drupal\Core\Session\AccountInterface $account */
    $account = \Drupal::service('current_user');

    if (
      $account->hasPermission('administer forex journal symbol') === FALSE ||
      $this->ownDashboard()
    ) {
      $query->condition('uid', $account->id());
    }

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultOperations(EntityInterface $entity) {
    $operations = parent::getDefaultOperations($entity);
    $destination = \Drupal::service('redirect.destination')->getAsArray();
    foreach ($operations as $key => $operation) {
      $operations[$key]['query'] = $destination;
    }
    return $operations;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEntityIds() {
    $query = $this->fxJournalEntityQuery();

    $query->sort('changed', 'DESC');

    // Only add the pager if a limit is specified.
    if ($this->limit) {
      $query->pager($this->limit);
    }
    return $query->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $label = $this->entityType->getPluralLabel();

    $build = [
      'header' => [
        '#type' => 'html_tag',
        '#tag' => 'h2',
        '#value' => $label,
      ]
    ];

    $build['table'] = parent::render();

    $query = $this->fxJournalEntityQuery();

    $total = $query->count()->execute();

    $build['summary']['#markup'] = $this->t('Total @entity_label: @total', [
      '@total' => $total,
      '@entity_label' => $label,
    ]);

    $build['#cache'] = [
      'max-age' => 0,
    ];

    return $build;
  }

}
