<?php

namespace Drupal\fxjournal;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a forex journal event entity type.
 */
interface FxjournalEventInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the forex journal event title.
   *
   * @return string
   *   Title of the forex journal event.
   */
  public function getTitle();

  /**
   * Sets the forex journal event title.
   *
   * @param string $title
   *   The forex journal event title.
   *
   * @return \Drupal\fxjournal\FxjournalEventInterface
   *   The called forex journal event entity.
   */
  public function setTitle($title);

  /**
   * Gets the forex journal event importance.
   *
   * @return string
   *   Importance of the forex journal event.
   */
  public function getImportance();

  /**
   * Sets the forex journal event importance.
   *
   * @param string $importance
   *   The forex journal event importance.
   *
   * @return \Drupal\fxjournal\FxjournalEventInterface
   *   The called forex journal event entity.
   */
  public function setImportance($importance);

  /**
   * Gets the forex journal event creation timestamp.
   *
   * @return int
   *   Creation timestamp of the forex journal event.
   */
  public function getCreatedTime();

  /**
   * Sets the forex journal event creation timestamp.
   *
   * @param int $timestamp
   *   The forex journal event creation timestamp.
   *
   * @return \Drupal\fxjournal\FxjournalEventInterface
   *   The called forex journal event entity.
   */
  public function setCreatedTime($timestamp);

}
