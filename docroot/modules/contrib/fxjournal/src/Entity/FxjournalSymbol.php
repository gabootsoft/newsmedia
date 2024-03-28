<?php

namespace Drupal\fxjournal\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\fxjournal\FxjournalSymbolInterface;
use Drupal\user\UserInterface;

/**
 * Defines the forex journal symbol entity class.
 *
 * @ContentEntityType(
 *   id = "fxjournal_symbol",
 *   label = @Translation("Forex Journal Symbol"),
 *   label_collection = @Translation("Forex Journal Symbols"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\fxjournal\FxjournalSymbolListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\fxjournal\FxjournalSymbolAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\fxjournal\Form\FxjournalSymbolForm",
 *       "edit" = "Drupal\fxjournal\Form\FxjournalSymbolForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "fxjournal_symbol",
 *   data_table = "fxjournal_symbol_field_data",
 *   revision_table = "fxjournal_symbol_revision",
 *   revision_data_table = "fxjournal_symbol_field_revision",
 *   show_revision_ui = TRUE,
 *   translatable = TRUE,
 *   admin_permission = "administer forex journal symbol",
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
 *     "add-form" = "/fxjournal/symbol/add",
 *     "canonical" = "/fxjournal/symbol/{fxjournal_symbol}/show",
 *     "edit-form" = "/fxjournal/symbol/{fxjournal_symbol}/edit",
 *     "delete-form" = "/fxjournal/symbol/{fxjournal_symbol}/delete",
 *     "collection" = "/admin/content/fxjournal-symbols"
 *   },
 *   field_ui_base_route = "entity.fxjournal_symbol.settings"
 * )
 */
class FxjournalSymbol extends RevisionableContentEntityBase implements FxjournalSymbolInterface {

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
  public function getDigits() {
    return $this->digits->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDigits($digits) {
    $this->digits = $digits;

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
      ->setLabel(t('Symbol'))
      ->setDescription(t('The forex journal symbol.'))
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

    $fields['digits'] = BaseFieldDefinition::create('integer')
      ->setRevisionable(TRUE)
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Digits'))
      ->setDescription(t('The number of meaningful digits after the decimal point'))
      ->setDefaultValue([5])
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
      ->setDescription(t('The user ID of the forex journal symbol author.'))
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
      ->setDescription(t('The time that the forex journal symbol was created.'))
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
