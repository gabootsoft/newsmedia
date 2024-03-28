<?php

namespace Drupal\Tests\fxjournal\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\Url;
use Drupal\Tests\fxjournal\Functional\FxjournalEntityTrait;

/**
 * Tests the symbols related functionality.
 *
 * @group fxjournal
 */
class FxjournalSymbolTest extends BrowserTestBase {

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
   * Creates a trader Symbol.
   */
  protected function createTrader() {
    return $this->drupalCreateUser([
      'view forex journal symbol',
      'create forex journal symbol',
      'edit forex journal symbol',
      'delete forex journal symbol',
    ]);

  }

  /**
   * Tests the Symbol related functionality.
   */
  public function testSymbol() {
    $trader = $this->createTrader();

    $session = $this->assertSession();

    $page = $this->getSession()->getPage();

    $this->drupalLogin($trader);

    $symbol = $this->createSymbol($trader->id());

    $bad_trader = $this->createTrader();

    $this->drupalLogin($bad_trader);

    $routes = [
      'entity.fxjournal_symbol.delete_form',
      'entity.fxjournal_symbol.edit_form',
    ];

    $params = [
      'fxjournal_symbol' => $symbol->id(),
    ];

    // Access Denied for the "bad" trader.
    foreach ($routes as $route) {
      $path = Url::fromRoute($route, $params);
      $this->drupalGet($path);
      $session->statusCodeEquals(403);
    }

    $this->drupalLogin($trader);

    // Add a Symbol.
    $path = Url::fromRoute('entity.fxjournal_symbol.add_form');

    $this->drupalGet($path);
    $session->pageTextContains('Add forex journal symbol');
    $session->statusCodeEquals(200);

    $values = $this->generateSymbolValues();

    $page->fillField('edit-title-0-value', $values['title']);
    $page->fillField('edit-digits-0-value', $values['digits']);

    $page->pressButton('Save');

    $session->pageTextContains('New forex journal symbol ' . $values['title'] . ' has been created');

    // Edit the Symbol.
    $symbol = $this->createSymbol($trader->id());

    $path = Url::fromRoute('entity.fxjournal_symbol.edit_form', [
      'fxjournal_symbol' => $symbol->id(),
    ]);

    $this->drupalGet($path);
    $session->statusCodeEquals(200);
    $session->pageTextContains('Edit ' . $symbol->getTitle());

    $values = $this->generateSymbolValues();

    $title_value = $values['title'];

    $page->fillField('edit-title-0-value', $title_value);
    $page->fillField('edit-digits-0-value', $values['digits']);

    $page->pressButton('Save');

    $session->pageTextContains('The forex journal symbol ' . $title_value . ' has been updated');

    // Delete the Symbol.
    $path = Url::fromRoute('entity.fxjournal_symbol.delete_form', [
      'fxjournal_symbol' => $symbol->id(),
    ]);

    $this->drupalGet($path);
    $session->pageTextContains('Are you sure you want to delete the forex journal symbol');

    $page->pressButton('Delete');

    $session->pageTextContains('The forex journal symbol ' . $title_value . ' has been deleted');
  }

}
