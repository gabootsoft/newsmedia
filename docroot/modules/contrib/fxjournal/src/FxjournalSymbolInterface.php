<?php

namespace Drupal\fxjournal;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a forex journal symbol entity type.
 */
interface FxjournalSymbolInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the forex journal symbol title.
   *
   * @return string
   *   Title of the forex journal symbol.
   */
  public function getTitle();

  /**
   * Sets the forex journal symbol title.
   *
   * @param string $title
   *   The forex journal symbol title.
   *
   * @return \Drupal\fxjournal\FxjournalSymbolInterface
   *   The called forex journal symbol entity.
   */
  public function setTitle($title);

  /**
   * Gets the forex journal symbol digits.
   *
   * @return string
   *   Digits of the forex journal symbol.
   */
  public function getDigits();

  /**
   * Sets the forex journal symbol digits.
   *
   * @param string $digits
   *   The forex journal symbol digits.
   *
   * @return \Drupal\fxjournal\FxjournalSymbolInterface
   *   The called forex journal symbol entity.
   */
  public function setDigits($digits);

  /**
   * Gets the forex journal symbol creation timestamp.
   *
   * @return int
   *   Creation timestamp of the forex journal symbol.
   */
  public function getCreatedTime();

  /**
   * Sets the forex journal symbol creation timestamp.
   *
   * @param int $timestamp
   *   The forex journal symbol creation timestamp.
   *
   * @return \Drupal\fxjournal\FxjournalSymbolInterface
   *   The called forex journal symbol entity.
   */
  public function setCreatedTime($timestamp);

}
