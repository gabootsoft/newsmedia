<?php

namespace Drupal\fxjournal\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\fxjournal\FxjournalAccountInterface;
use Drupal\user\UserInterface;

/**
 * Defines the forex journal account entity class.
 *
 * @ContentEntityType(
 *   id = "fxjournal_account",
 *   label = @Translation("Forex Journal Account"),
 *   label_collection = @Translation("Forex Journal Accounts"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\fxjournal\FxjournalAccountListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\fxjournal\FxjournalAccountAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\fxjournal\Form\FxjournalAccountForm",
 *       "edit" = "Drupal\fxjournal\Form\FxjournalAccountForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "fxjournal_account",
 *   data_table = "fxjournal_account_field_data",
 *   revision_table = "fxjournal_account_revision",
 *   revision_data_table = "fxjournal_account_field_revision",
 *   show_revision_ui = TRUE,
 *   translatable = TRUE,
 *   admin_permission = "administer forex journal account",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "langcode" = "langcode",
 *     "label" = "login",
 *     "uuid" = "uuid"
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log"
 *   },
 *   links = {
 *     "add-form" = "/fxjournal/account/add",
 *     "canonical" = "/fxjournal/account/{fxjournal_account}/show",
 *     "edit-form" = "/fxjournal/account/{fxjournal_account}/edit",
 *     "delete-form" = "/fxjournal/account/{fxjournal_account}/delete",
 *     "collection" = "/admin/content/fxjournal-accounts"
 *   },
 *   field_ui_base_route = "entity.fxjournal_account.settings"
 * )
 */
class FxjournalAccount extends RevisionableContentEntityBase implements FxjournalAccountInterface {

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
  public function getLogin() {
    return $this->login->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLogin($login) {
    $this->login = $login;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCompany() {
    return $this->company->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCompany($company) {
    $this->company = $company;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrency() {
    return $this->currency->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCurrency($currency) {
    $this->currency = $currency;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLeverage() {
    return $this->leverage->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLeverage($leverage) {
    $this->leverage = $leverage;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->type->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setType($type) {
    $this->type = $type;

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

    $fields['login'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setLabel(t('Login'))
      ->setDescription(t('The account number.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['company'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setLabel(t('Company'))
      ->setDescription(t('The company that serves the account.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['currency'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setLabel(t('Currency'))
      ->setDescription(t('The deposit currency.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['leverage'] = BaseFieldDefinition::create('integer')
      ->setRevisionable(TRUE)
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Leverage'))
      ->setDescription(t('The account leverage'))
      ->setDefaultValue([1])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_integer',
        'label' => 'above',
        'weight' => -1,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['type'] = BaseFieldDefinition::create('list_string')
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Type'))
      ->setDescription(t('The account type.'))
      ->setRequired(TRUE)
      ->setSettings([
        'allowed_values' => [
          'Demo' => 'Demo',
          'Real' => 'Real',
        ],
      ])
      ->setDefaultValue([
        [
          'value' => 'Demo',
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setTranslatable(TRUE);
    return $fields;
  }

}
