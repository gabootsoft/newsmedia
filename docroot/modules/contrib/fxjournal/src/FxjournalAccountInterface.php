<?php

namespace Drupal\fxjournal;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a forex journal account entity type.
 */
interface FxjournalAccountInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the forex journal account login.
   *
   * @return string
   *   Login of the forex journal account.
   */
  public function getLogin();

  /**
   * Sets the forex journal account login.
   *
   * @param string $login
   *   The forex journal account login.
   *
   * @return \Drupal\fxjournal\FxjournalAccountInterface
   *   The called forex journal account entity.
   */
  public function setLogin($login);

  /**
   * Gets the forex journal account company.
   *
   * @return string
   *   Company of the forex journal account.
   */
  public function getCompany();

  /**
   * Sets the forex journal account company.
   *
   * @param string $company
   *   The forex journal account company.
   *
   * @return \Drupal\fxjournal\FxjournalAccountInterface
   *   The called forex journal account entity.
   */
  public function setCompany($company);

  /**
   * Gets the forex journal account currency.
   *
   * @return string
   *   Currency of the forex journal account.
   */
  public function getCurrency();

  /**
   * Sets the forex journal account currency.
   *
   * @param string $currency
   *   The forex journal account currency.
   *
   * @return \Drupal\fxjournal\FxjournalAccountInterface
   *   The called forex journal account entity.
   */
  public function setCurrency($currency);

  /**
   * Gets the forex journal account leverage.
   *
   * @return string
   *   Leverage of the forex journal account.
   */
  public function getLeverage();

  /**
   * Sets the forex journal account leverage.
   *
   * @param string $leverage
   *   The forex journal account leverage.
   *
   * @return \Drupal\fxjournal\FxjournalAccountInterface
   *   The called forex journal account entity.
   */
  public function setLeverage($leverage);

  /**
   * Gets the forex journal account type.
   *
   * @return string
   *   Type of the forex journal account.
   */
  public function getType();

  /**
   * Sets the forex journal account type.
   *
   * @param string $type
   *   The forex journal account type.
   *
   * @return \Drupal\fxjournal\FxjournalAccountInterface
   *   The called forex journal account entity.
   */
  public function setType($type);

  /**
   * Gets the forex journal account creation timestamp.
   *
   * @return int
   *   Creation timestamp of the forex journal account.
   */
  public function getCreatedTime();

  /**
   * Sets the forex journal account creation timestamp.
   *
   * @param int $timestamp
   *   The forex journal account creation timestamp.
   *
   * @return \Drupal\fxjournal\FxjournalAccountInterface
   *   The called forex journal account entity.
   */
  public function setCreatedTime($timestamp);

}
