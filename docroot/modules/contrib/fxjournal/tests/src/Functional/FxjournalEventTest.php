<?php

namespace Drupal\Tests\fxjournal\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\Url;
use Drupal\Tests\fxjournal\Functional\FxjournalEntityTrait;

/**
 * Tests the events related functionality.
 *
 * @group fxjournal
 */
class FxjournalEventTest extends BrowserTestBase {

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
   * Creates a trader Event.
   */
  protected function createTrader() {
    return $this->drupalCreateUser([
      'view forex journal event',
      'create forex journal event',
      'edit forex journal event',
      'delete forex journal event',
    ]);

  }

  /**
   * Tests the Event related functionality.
   */
  public function testEvent() {
    $trader = $this->createTrader();

    $session = $this->assertSession();

    $page = $this->getSession()->getPage();

    $this->drupalLogin($trader);

    $event = $this->createEvent($trader->id());

    $bad_trader = $this->createTrader();

    $this->drupalLogin($bad_trader);

    $routes = [
      'entity.fxjournal_event.delete_form',
      'entity.fxjournal_event.edit_form',
    ];

    $params = [
      'fxjournal_event' => $event->id(),
    ];

    // Access Denied for the "bad" trader.
    foreach ($routes as $route) {
      $path = Url::fromRoute($route, $params);
      $this->drupalGet($path);
      $session->statusCodeEquals(403);
    }

    $this->drupalLogin($trader);

    // Add a Event.
    $path = Url::fromRoute('entity.fxjournal_event.add_form');

    $this->drupalGet($path);
    $session->pageTextContains('Add forex journal event');
    $session->statusCodeEquals(200);

    $values = $this->generateEventValues();

    $page->fillField('edit-title-0-value', $values['title']);
    $page->fillField('edit-importance-0-value', $values['importance']);

    $page->pressButton('Save');

    $session->pageTextContains('New forex journal event ' . $values['title'] . ' has been created');

    // Edit the Event.
    $event = $this->createEvent($trader->id());

    $path = Url::fromRoute('entity.fxjournal_event.edit_form', [
      'fxjournal_event' => $event->id(),
    ]);

    $this->drupalGet($path);
    $session->statusCodeEquals(200);
    $session->pageTextContains('Edit ' . $event->getTitle());

    $values = $this->generateEventValues();

    $title_value = $values['title'];

    $page->fillField('edit-title-0-value', $title_value);
    $page->fillField('edit-importance-0-value', $values['importance']);

    $page->pressButton('Save');

    $session->pageTextContains('The forex journal event ' . $title_value . ' has been updated');

    // Delete the Event.
    $path = Url::fromRoute('entity.fxjournal_event.delete_form', [
      'fxjournal_event' => $event->id(),
    ]);

    $this->drupalGet($path);
    $session->pageTextContains('Are you sure you want to delete the forex journal event');

    $page->pressButton('Delete');

    $session->pageTextContains('The forex journal event ' . $title_value . ' has been deleted');
  }

}
