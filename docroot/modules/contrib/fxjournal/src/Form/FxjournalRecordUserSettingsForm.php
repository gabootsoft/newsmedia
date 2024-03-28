<?php

namespace Drupal\fxjournal\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\fxjournal\Processor;
use Drupal\Core\Render\Renderer;

/**
 * Provides a Forex Journal form.
 */
class FxjournalRecordUserSettingsForm extends FormBase {

  /**
   * The fxjournal processor.
   *
   * @var \Drupal\fxjournal\FxjournalProcessor
   */
  protected $processor;

  /**
   * The current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The renderer instance.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Class constructor.
   */
  public function __construct(
    Processor $processor,
    AccountProxyInterface $current_user,
    Renderer $renderer) {
    $this->processor = $processor;
    $this->currentUser = $current_user;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('fxjournal.processor'),
      $container->get('current_user'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'fxjournal_record_user_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // $settings = $this->processor->getUserSettings($this->currentUser->id());
    $settings = [];

    $form['account_currency'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Broker account currencies'),
      '#description' => $this->t('A comma-separated values such as EUR, USD, GBP'),
    ];

    if ($this->setAsRequired('account_currency', $settings)) {
      $form['account_currency']['#required'] = TRUE;
    }
    else {
      $form['account_currencies_existing'] = [
        '#markup' => $this->renderSettings(
          $this->t('Existing account currencies'),
          $settings['account_currency']
        ),
      ];
    }

    $form['symbol'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Trade symbols'),
      '#description' => $this->t('A comma-separated values such as EURUSD, USDJPY.'),
    ];

    if ($this->setAsRequired('symbol', $settings)) {
      $form['symbol']['#required'] = TRUE;
    }
    else {
      $form['symbols_existing'] = [
        '#markup' => $this->renderSettings(
          $this->t('Existing Symbols'),
          $settings['symbol']
        ),
      ];
    }

    $form['pre_trade'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Preliminary trade check items'),
      '#description' => $this->t('Comma-separated items such as "Strong Signal", "Trend reversal"'),
    ];

    if ($this->setAsRequired('pre_trade', $settings)) {
      $form['pre_trade']['#required'] = TRUE;
    }
    else {
      $form['pre_trade_existing'] = [
        '#markup' => $this->renderSettings(
          $this->t('Existing Preliminary Trade Items'),
          $settings['pre_trade']
        ),
      ];
    }

    $form['intra_trade'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Intra-trade check items'),
      '#description' => $this->t('Comma-separated items such as "Price peak", "Trend continues"'),
    ];

    if ($this->setAsRequired('intra_trade', $settings)) {
      $form['intra_trade']['#required'] = TRUE;
    }
    else {
      $form['intra_trade_existing'] = [
        '#markup' => $this->renderSettings(
          $this->t('Existing Intra-Trade Items'),
          $settings['intra_trade']
        ),
      ];
    }

    $form['post_trade'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Post-trade check items'),
      '#description' => $this->t('Comma-separated items such as "Went as planned", "Not a good trade"'),
    ];

    if ($this->setAsRequired('post_trade', $settings)) {
      $form['post_trade']['#required'] = TRUE;
    }
    else {
      $form['post_trade_existing'] = [
        '#markup' => $this->renderSettings(
          $this->t('Existing Post-Trade Items'),
          $settings['post_trade']
        ),
      ];
    }

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fields = $this->processor->getFields();

    $data = [];

    foreach ($fields as $field) {
      $value = $form_state->getValue($field);
      if (!empty($value)) {
        $data[$field] = $value;
      }
    }

    $this->processor->setUserSettings($data, $this->currentUser->id());

    $this->messenger()->addStatus($this->t('The settings have been saved.'));
  }

  /**
   * Checks if a key needs to be set as required.
   *
   * @param string $key
   *   The key.
   * @param mixed $settings
   *   The settings.
   *
   * @return bool
   *   The result of the check.
   */
  protected function setAsRequired(string $key, $settings): bool {
    return (
      isset($settings[$key]) &&
      is_array($settings[$key]) &&
      !empty($settings[$key])
    ) == FALSE;
  }

  /**
   * Renders the settings.
   *
   * @param string $title
   *   The title.
   * @param array $settings
   *   The settings.
   *
   * @return string
   *   The HTML content.
   */
  protected function renderSettings($title, array $settings) {
    $renderable = [
      '#theme' => 'fxjournal_existing_settings',
      '#title' => $title,
      '#settings' => $settings,
    ];

    return $this->renderer->render($renderable);
  }

}
