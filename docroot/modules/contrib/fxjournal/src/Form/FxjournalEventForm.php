<?php

namespace Drupal\fxjournal\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the forex journal event entity edit forms.
 */
class FxjournalEventForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New forex journal event %label has been created.', $message_arguments));
      $this->logger('fxjournal_event')->notice('Created new forex journal event %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The forex journal event %label has been updated.', $message_arguments));
      $this->logger('fxjournal_event')->notice('Updated new forex journal event %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.fxjournal_event.canonical', ['fxjournal_event' => $entity->id()]);
  }

}
