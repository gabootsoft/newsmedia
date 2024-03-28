<?php

namespace Drupal\fxjournal\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\fxjournal\FxjournalEventInterface;
use Drupal\user\UserInterface;

/**
 * Defines the forex journal event entity class.
 *
 * @ContentEntityType(
 *   id = "fxjournal_event",
 *   label = @Translation("Forex Journal Event"),
 *   label_collection = @Translation("Forex Journal Events"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\fxjournal\FxjournalEventListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\fxjournal\FxjournalEventAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\fxjournal\Form\FxjournalEventForm",
 *       "edit" = "Drupal\fxjournal\Form\FxjournalEventForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "fxjournal_event",
 *   data_table = "fxjournal_event_field_data",
 *   revision_table = "fxjournal_event_revision",
 *   revision_data_table = "fxjournal_event_field_revision",
 *   show_revision_ui = TRUE,
 *   translatable = TRUE,
 *   admin_permission = "administer forex journal event",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "langcode" = "langcode",
 *     "label" = "title",
 *     "uuid" = "uuid"
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log"
 *   },
 *   links = {
 *     "add-form" = "/fxjournal/event/add",
 *     "canonical" = "/fxjournal/event/{fxjournal_event}/show",
 *     "edit-form" = "/fxjournal/event/{fxjournal_event}/edit",
 *     "delete-form" = "/fxjournal/event/{fxjournal_event}/delete",
 *     "collection" = "/admin/content/fxjournal-events"
 *   },
 *   field_ui_base_route = "entity.fxjournal_event.settings"
 * )
 */
class FxjournalEvent extends RevisionableContentEntityBase implements FxjournalEventInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += ['uid' => \Drupal::currentUser()->id()];
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->title->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->title = $title;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getImportance() {
    return $this->importance->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setImportance($importance) {
    $this->importance = $importance;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->created->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->created = $timestamp;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->uid->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->uid->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->uid = $uid;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->uid = $account->id();

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setLabel(t('Event'))
      ->setDescription(t('The forex journal event.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['importance'] = BaseFieldDefinition::create('integer')
      ->setRevisionable(TRUE)
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Importance'))
      ->setDescription(t('The event importance'))
      ->setDefaultValue([1])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -8,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_decimal',
        'label' => 'above',
        'weight' => -8,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setLabel(t('Author'))
      ->setDescription(t('The user ID of the forex journal event author.'))
      ->setSetting('target_type', 'user')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => -7,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => -7,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the forex journal event was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => -6,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => -6,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the forex journal symbol was last edited.'));

    return $fields;
  }

}
