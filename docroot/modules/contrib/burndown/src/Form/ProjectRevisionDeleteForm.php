<?php

namespace Drupal\burndown\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Project revision.
 *
 * @ingroup burndown
 */
class ProjectRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Project revision.
   *
   * @var \Drupal\burndown\Entity\ProjectInterface
   */
  protected $revision;

  /**
   * The Project storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $projectStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->projectStorage = $container->get('entity_type.manager')->getStorage('burndown_project');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'burndown_project_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', [
      '%revision-date' => \Drupal::service('date.formatter')->format($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.burndown_project.version_history', ['burndown_project' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $burndown_project_revision = NULL) {
    $this->revision = $this->ProjectStorage->loadRevision($burndown_project_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->ProjectStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')
      ->notice(
        'Project: deleted %title revision %revision.',
        [
          '%title' => $this->revision->label(),
          '%revision' => $this->revision->getRevisionId(),
        ]
      );
    $this->messenger()
      ->addMessage(
        $this->t('Revision from %revision-date of Project %title has been deleted.',
        [
          '%revision-date' => \Drupal::service('date.formatter')->format($this->revision->getRevisionCreationTime()),
          '%title' => $this->revision->label(),
        ]
      )
    );
    $form_state->setRedirect(
      'entity.burndown_project.canonical',
       ['burndown_project' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {burndown_project_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.burndown_project.version_history',
         ['burndown_project' => $this->revision->id()]
      );
    }
  }

}
