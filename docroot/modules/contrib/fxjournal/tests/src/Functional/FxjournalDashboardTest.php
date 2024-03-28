<?php

namespace Drupal\Tests\fxjournal\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\Url;
use Drupal\fxjournal\Entity\FxjournalSymbol;
use Drupal\Tests\fxjournal\Functional\FxjournalEntityTrait;

/**
 * Tests the dashboard related functionality.
 *
 * @group fxjournal
 */
class FxjournalDashboardTest extends BrowserTestBase {

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
   * Creates a Trader.
   */
  protected function createTrader() {
    return $this->drupalCreateUser([
      'view forex journal record',
      'create forex journal record',
      'edit forex journal record',
      'delete forex journal record',
    ]);

  }

  /**
   * Tests the Dashboard related functionality.
   */
  public function testDashboard() {
    /** @var \Drupal\user\Entity\User $trader */
    $trader = $this->createTrader();

    $session = $this->assertSession();

    $record = $this->createRecord($trader->id());

    /** @var \Drupal\user\Entity\User $bad_trader */
    $bad_trader = $this->createTrader();
    $this->drupalLogin($bad_trader);

    // Access Denied for the "bad" trader.
    $path = Url::fromRoute('fxjournal.user.dashboard', [
      'user' => $trader->id(),
    ]);
    $this->drupalGet($path);
    $session->statusCodeEquals(403);

    $this->drupalLogin($trader);

    $account = $record->getTradeAccount();
    $symbol = $record->getSymbol();
    $event = current($record->getEvents());

    // Test the Dashboard.
    $this->drupalGet($path);
    $session->statusCodeEquals(200);

    // Test the accounts related part.
    $session->pageTextContains($account->id());
    $session->pageTextContains($account->getLogin());
    $session->pageTextContains($account->getCompany());
    $session->pageTextContains($account->getCurrency());
    $session->pageTextContains($account->getLeverage());
    $session->pageTextContains($account->getType());
    $session->pageTextContains('Total forex journal account entities: 1');

    // Test the symbol related part.
    $session->pageTextContains($symbol->id());
    $session->pageTextContains($symbol->getTitle());
    $session->pageTextContains($symbol->getDigits());
    $session->pageTextContains('Total forex journal symbol entities: 1');

    // Test the events related part.
    $session->pageTextContains($event->id());
    $session->pageTextContains($event->getTitle());
    $session->pageTextContains($event->getImportance());
    $session->pageTextContains('Total forex journal event entities: 1');

    // Test the records related part.
    $session->pageTextContains($record->id());
    $session->pageTextContains($record->getTradeAccount()->getCurrency());
    $session->pageTextContains($record->getOpenDateTime());
    $session->pageTextContains($record->getTicket());
    $session->pageTextContains($record->getType());
    $session->pageTextContains($record->getVolume());
    $session->pageTextContains($record->getSymbol()->getTitle());
    $session->pageTextContains($record->getPriceOpen());
    $session->pageTextContains($record->getStopLoss());
    $session->pageTextContains($record->getTakeProfit());
    $session->pageTextContains($record->getCloseDateTime());
    $session->pageTextContains($record->getPriceClose());
    $session->pageTextContains($record->getProfit());
    $session->pageTextContains('Total forex journal record entities: 1');

  }

}
