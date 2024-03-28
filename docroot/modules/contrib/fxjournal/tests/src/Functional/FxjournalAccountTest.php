<?php

namespace Drupal\Tests\fxjournal\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\Url;
use Drupal\Tests\fxjournal\Functional\FxjournalEntityTrait;

/**
 * Tests the accounts related functionality.
 *
 * @group fxjournal
 */
class FxjournalAccountTest extends BrowserTestBase {

  use FxjournalEntityTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = ['fxjournal'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Creates a trader account.
   */
  protected function createTrader() {
    return $this->drupalCreateUser([
      'view forex journal account',
      'create forex journal account',
      'edit forex journal account',
      'delete forex journal account',
    ]);

  }

  /**
   * Tests the account related functionality.
   */
  public function testAccount() {
    $trader = $this->createTrader();

    $session = $this->assertSession();

    $page = $this->getSession()->getPage();

    $this->drupalLogin($trader);

    $account = $this->createAccount($trader->id());

    $bad_trader = $this->createTrader();

    $this->drupalLogin($bad_trader);

    $routes = [
      'entity.fxjournal_account.delete_form',
      'entity.fxjournal_account.edit_form',
    ];

    $params = [
      'fxjournal_account' => $account->id(),
    ];

    // Access Denied for the "bad" trader.
    foreach ($routes as $route) {
      $path = Url::fromRoute($route, $params);
      $this->drupalGet($path);
      $session->statusCodeEquals(403);
    }

    $this->drupalLogin($trader);

    // Add an account.
    $path = Url::fromRoute('entity.fxjournal_account.add_form');

    $this->drupalGet($path);
    $session->pageTextContains('Add forex journal account');
    $session->statusCodeEquals(200);

    $values = $this->generateAccountValues();

    $page->fillField('edit-login-0-value', $values['login']);
    $page->fillField('edit-company-0-value', $values['company']);
    $page->fillField('edit-currency-0-value', $values['currency']);
    $page->fillField('edit-leverage-0-value', $values['leverage']);
    $page->selectFieldOption('edit-type', $values['type']);

    $page->pressButton('Save');

    $session->pageTextContains('New forex journal account ' . $values['login'] . ' has been created');

    // Edit the account.
    $account = $this->createAccount($trader->id());

    $path = Url::fromRoute('entity.fxjournal_account.edit_form', [
      'fxjournal_account' => $account->id(),
    ]);

    $this->drupalGet($path);
    $session->statusCodeEquals(200);
    $session->pageTextContains('Edit ' . $account->getLogin());

    $values = $this->generateAccountValues();

    $login_value = $values['login'];

    $page->fillField('edit-login-0-value', $login_value);
    $page->fillField('edit-company-0-value', $values['company']);
    $page->fillField('edit-currency-0-value', $values['currency']);
    $page->fillField('edit-leverage-0-value', $values['leverage']);
    $page->selectFieldOption('edit-type', $values['type']);

    $page->pressButton('Save');

    $session->pageTextContains('The forex journal account ' . $values['login'] . ' has been updated');

    // Delete the account.
    $path = Url::fromRoute('entity.fxjournal_account.delete_form', [
      'fxjournal_account' => $account->id(),
    ]);

    $this->drupalGet($path);
    $session->pageTextContains('Are you sure you want to delete the forex journal account');

    $page->pressButton('Delete');

    $session->pageTextContains('The forex journal account ' . $login_value . ' has been deleted');
  }

}
