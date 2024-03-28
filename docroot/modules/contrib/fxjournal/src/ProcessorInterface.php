<?php

namespace Drupal\fxjournal;

/**
 * Provides an interface defining the Processor methods.
 */
interface ProcessorInterface {

  /**
   * Gets the user trading accounts.
   *
   * @param int $user_id
   *   The user id.
   * @param bool $select_options
   *   Return a key-value array if TRUE.
   *
   * @return array
   *   The user trading accounts.
   */
  public function getAccounts(int $user_id, bool $select_options = TRUE): array;

  /**
   * Gets the user symbols.
   *
   * @param int $user_id
   *   The user id.
   * @param bool $select_options
   *   Return a key-value array if TRUE.
   *
   * @return array
   *   The user symbols.
   */
  public function getSymbols(int $user_id, bool $select_options = TRUE): array;

  /**
   * Gets the user events.
   *
   * @param int $user_id
   *   The user id.
   * @param bool $select_options
   *   Return a key-value array if TRUE.
   *
   * @return array
   *   The user events.
   */
  public function getEvents(int $user_id, bool $select_options = TRUE): array;

}
