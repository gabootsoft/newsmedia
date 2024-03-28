<?php

namespace Drupal\digital_marketing_checklist\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\digital_marketing_checklist\DigitalMarketingChecklistInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form to delete section items.
 *
 * @internal
 */
class DeleteSectionItemsForm extends ConfirmFormBase {

  /**
   * List of sections to remove.
   *
   * @var array
   */
  private $sections;

  /**
   * The production checklist manager.
   *
   * @var \Drupal\digital_marketing_checklist\DigitalMarketingChecklistInterface
   */
  protected $productionChecklist;

  /**
   * Constructs a new DeleteSectionItemsForm object.
   *
   * @param \Drupal\digital_marketing_checklist\DigitalMarketingChecklistInterface $production_checklist
   *   The production checklist manager.
   */
  public function __construct(DigitalMarketingChecklistInterface $production_checklist) {
    $this->productionChecklist = $production_checklist;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('digital_marketing_checklist')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'digital_marketing_checklist_delete_items';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    // @todo get section titles
    $sectionTitles = $this->productionChecklist->getSectionTitles($this->sections);
    return $this->t('Do you want to clear the items from the following sections: %sections?',
      ['%sections' => implode(', ', $sectionTitles)]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Clear section items');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('digital_marketing_checklist.sections');
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param string $sections
   *   The sections to disable.
   *
   * @throws \Exception
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $sections = '') {
    $this->sections = explode(',', $sections);
    // @todo check sections from existing ones
    if (empty($sections)) {
      throw new \Exception(t('No sections were found.'));
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::configFactory()->getEditable('digital_marketing_checklist.settings');
    $sections = $config->get('sections');
    foreach ($this->sections as $sectionKey) {
      $sections[$sectionKey] = 0;
    }
    $config->set('sections', $sections)->save();

    $clearedItems = $this->productionChecklist->clearItems($sections);
    if (!empty($clearedItems)) {
      $this->logger('user')->notice('Cleared sections items: %items',
        ['%items' => implode(', ', $clearedItems)]
      );
      $this->messenger()->addStatus(t('Cleared sections items: @items.', ['@items' => implode(', ', $clearedItems)]));
    }
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
