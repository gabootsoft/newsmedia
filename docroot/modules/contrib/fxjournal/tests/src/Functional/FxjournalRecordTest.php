<?php

namespace Drupal\Tests\fxjournal\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\Url;
use Drupal\fxjournal\Entity\FxjournalSymbol;
use Drupal\Tests\fxjournal\Functional\FxjournalEntityTrait;

/**
 * Tests the records related functionality.
 *
 * @group fxjournal
 */
class FxjournalRecordTest extends BrowserTestBase {

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
   * Tests the Record related functionality.
   */
  public function testRecord() {
    /** @var \Drupal\user\Entity\User $trader */
    $trader = $this->createTrader();

    $session = $this->assertSession();

    $page = $this->getSession()->getPage();

    $routes = [
      'entity.fxjournal_record.delete_form',
      'entity.fxjournal_record.edit_form',
    ];

    $record = $this->createRecord($trader->id());
    $params = [
      'fxjournal_record' => $record->id(),
    ];

    /** @var \Drupal\user\Entity\User $bad_trader */
    $bad_trader = $this->createTrader();
    $this->drupalLogin($bad_trader);

    /** @var \Drupal\fxjournal\Entity\FxjournalEvent $external_event */
    $external_event = $this->createEvent($bad_trader->id());

    /** @var \Drupal\fxjournal\Entity\FxjournalAccount $external_account */
    $external_account = $this->createAccount($bad_trader->id());

    /** @var \Drupal\fxjournal\Entity\FxjournalSymbol $external_symbol */
    $external_symbol = $this->createSymbol($bad_trader->id());

    // Access Denied for the "bad" trader.
    foreach ($routes as $route) {
      $path = Url::fromRoute($route, $params);
      $this->drupalGet($path);
      $session->statusCodeEquals(403);
    }

    $this->drupalLogin($trader);

    $path = Url::fromRoute('entity.fxjournal_record.add_form');

    $this->drupalGet($path);
    $session->pageTextContains('Add forex journal record');
    $session->statusCodeEquals(200);

    // The external event should not be visible.
    $session->pageTextNotContains($external_event->getTitle());

    // The external account should not be selectable.
    $session->optionNotExists('Trade Account', $external_account->getLogin());

    // The external symbol should not be selectable.
    $session->optionNotExists('Symbol', $external_symbol->getTitle());

    // Add a Record.
    $path = Url::fromRoute('entity.fxjournal_record.add_form');

    $this->drupalGet($path);
    $session->pageTextContains('Add forex journal record');
    $session->statusCodeEquals(200);

    $values = $this->generateRecordValues();

    $page->checkField('edit-events-1');
    $page->fillField('edit-ticket-0-value', $values['ticket']);
    $page->fillField('edit-open-datetime-0-value-date', date('Y-m-d'));
    $page->fillField('edit-open-datetime-0-value-time', date('H:i:s'));
    $page->fillField('edit-type', $values['type']);
    $page->fillField('edit-volume-0-value', $values['volume']);
    $page->fillField('edit-price-open-0-value', $values['price_open']);
    $page->fillField('edit-stop-loss-0-value', $values['stop_loss']);
    $page->fillField('edit-take-profit-0-value', $values['take_profit']);
    $page->fillField('edit-commission-0-value', $values['commission']);
    $page->fillField('edit-close-datetime-0-value-date', date('Y-m-d'));
    $page->fillField('edit-close-datetime-0-value-time', date('H:i:s', \time() + 3600));
    $page->fillField('edit-price-close-0-value', $values['price_close']);
    $page->fillField('edit-swap-0-value', $values['swap']);
    $page->fillField('edit-profit-0-value', $values['profit']);

    $page->pressButton('Save');

    $session->pageTextContains('New forex journal record ' . $values['ticket'] . ' has been created');

    // Edit the Record.
    $record = $this->createRecord($trader->id());

    $path = Url::fromRoute('entity.fxjournal_record.edit_form', [
      'fxjournal_record' => $record->id(),
    ]);

    $this->drupalGet($path);
    $session->statusCodeEquals(200);
    $session->pageTextContains('Edit ' . $record->getTicket());

    $values = $this->generateRecordValues();

    $ticket_value = $values['ticket'];

    $page->fillField('edit-ticket-0-value', $ticket_value);

    $page->pressButton('Save');

    $session->pageTextContains('The forex journal record ' . $ticket_value . ' has been updated');

    // Delete the Record.
    $path = Url::fromRoute('entity.fxjournal_record.delete_form', [
      'fxjournal_record' => $record->id(),
    ]);

    $this->drupalGet($path);
    $session->pageTextContains('Are you sure you want to delete the forex journal record');

    $page->pressButton('Delete');

    $session->pageTextContains('The forex journal record ' . $ticket_value . ' has been deleted');
  }

}
