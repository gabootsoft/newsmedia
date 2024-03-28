<?php

namespace Drupal\fxjournal\Entity;

use Drupal\Component\Datetime\DateTimePlus;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\fxjournal\FxjournalAccountInterface;
use Drupal\fxjournal\FxjournalEventInterface;
use Drupal\fxjournal\FxjournalRecordInterface;
use Drupal\fxjournal\FxjournalSymbolInterface;
use Drupal\user\UserInterface;

/**
 * Defines the forex journal record entity class.
 *
 * @ContentEntityType(
 *   id = "fxjournal_record",
 *   label = @Translation("Forex Journal Record"),
 *   label_collection = @Translation("Forex Journal Records"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\fxjournal\FxjournalRecordListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\fxjournal\FxjournalRecordAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\fxjournal\Form\FxjournalRecordForm",
 *       "edit" = "Drupal\fxjournal\Form\FxjournalRecordForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "fxjournal_record",
 *   data_table = "fxjournal_record_field_data",
 *   revision_table = "fxjournal_record_revision",
 *   revision_data_table = "fxjournal_record_field_revision",
 *   show_revision_ui = TRUE,
 *   translatable = TRUE,
 *   admin_permission = "administer forex journal record",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "langcode" = "langcode",
 *     "label" = "ticket",
 *     "uuid" = "uuid"
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log"
 *   },
 *   links = {
 *     "add-form" = "/fxjournal/record/add",
 *     "canonical" = "/fxjournal/record/{fxjournal_record}/show",
 *     "edit-form" = "/fxjournal/record/{fxjournal_record}/edit",
 *     "delete-form" = "/fxjournal/record/{fxjournal_record}/delete",
 *     "collection" = "/admin/content/fxjournal-records"
 *   },
 *   field_ui_base_route = "entity.fxjournal_record.settings"
 * )
 */
class FxjournalRecord extends RevisionableContentEntityBase implements FxjournalRecordInterface {

  use EntityChangedTrait;

  /**
   * Sets a Datetime entity field.
   *
   * @param int $timestamp
   *   The timestamp.
   * @param string $field
   *   The field name.
   */
  protected function setDatetime(int $timestamp, string $field) {
    $dt = DateTimePlus::createFromTimestamp($timestamp);
    $dt->setTimezone(new \DateTimeZone(DateTimeItemInterface::STORAGE_TIMEZONE));
    $dt_formatted = $dt->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
    $this->{$field} = $dt_formatted;
  }

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
  public function getTradeAccount() {
    return $this->trade_account->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setTradeAccount(FxjournalAccountInterface $account) {
    $this->trade_account->target_id = $account->id();
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEvents() {
    return $this->events->referencedEntities();
  }

  /**
   * {@inheritdoc}
   */
  public function addEvent(FxjournalEventInterface $event) {
    $this->events[] = ['target_id' => $event->id()];
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTicket() {
    return $this->ticket->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTicket($ticket) {
    $this->ticket = $ticket;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOpenDateTime() {
    return $this->open_datetime->date;
  }

  /**
   * {@inheritdoc}
   */
  public function setOpenDateTime(int $timestamp) {
    $this->setDatetime($timestamp, 'open_datetime');

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
  public function getVolume() {
    return $this->volume->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVolume($volume) {
    $this->volume = $volume;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSymbol() {
    return $this->symbol->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setSymbol(FxjournalSymbolInterface $symbol) {
    $this->symbol->target_id = $symbol->id();

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPriceOpen() {
    return $this->price_open->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPriceOpen(float $price_open) {
    $this->price_open = $price_open;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getStopLoss() {
    return $this->stop_loss->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setStopLoss(float $stop_loss) {
    $this->stop_loss = $stop_loss;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTakeProfit() {
    return $this->take_profit->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTakeProfit(float $take_profit) {
    $this->take_profit = $take_profit;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCommission() {
    return $this->commission->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCommission(float $commission) {
    $this->commission = $commission;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCloseDateTime() {
    return $this->close_datetime->date;
  }

  /**
   * {@inheritdoc}
   */
  public function setCloseDateTime(int $timestamp) {
    $this->setDatetime($timestamp, 'close_datetime');

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPriceClose() {
    return $this->price_close->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPriceClose(float $price_close) {
    $this->price_close = $price_close;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSwap() {
    return $this->swap->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSwap(float $swap) {
    $this->swap = $swap;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProfit() {
    return $this->profit->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setProfit(float $profit) {
    $this->profit = $profit;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTimeStamp($field_name) {
    $date = $this->get($field_name)->date;
    return (!empty($date)) ? $date->getTimeStamp() : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['events'] = BaseFieldDefinition::create('entity_reference')
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Events'))
      ->setDescription(t('What happened before, during and after the trade?'))
      ->setRequired(FALSE)
      ->setSettings([
        'target_type' => 'fxjournal_event',
        'allowed_values_function' => 'fxjournal_allowed_values_callback',
      ])
      ->setCardinality(-1)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons',
        'weight' => -25,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -25,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['trade_account'] = BaseFieldDefinition::create('entity_reference')
      ->setTranslatable(FALSE)
      ->setLabel(t('Trade Account'))
      ->setRequired(TRUE)
      ->setDescription(t('The position Trade Account'))
      ->setSettings([
        'target_type' => 'fxjournal_account',
        'allowed_values_function' => 'fxjournal_allowed_values_callback',
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'settings' => [
          'multiple_values' => FALSE,
        ],
        'weight' => -24,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -24,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['ticket'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setLabel(t('Ticket'))
      ->setDescription(t('The position Ticket.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -23,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -23,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['open_datetime'] = BaseFieldDefinition::create('datetime')
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE)
      ->setRequired(TRUE)
      ->setLabel(t('Open date'))
      ->setDescription(t('The trade Open Date.'))
      ->setDisplayOptions('form', [
        'type' => 'datetime_default',
        'weight' => -19,
      ])
      ->setDisplayOptions('view', [
        'type' => 'datetime_default',
        'label' => 'above',
        'weight' => -19,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['type'] = BaseFieldDefinition::create('list_string')
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Type'))
      ->setDescription(t('The position Type.'))
      ->setRequired(TRUE)
      ->setSettings([
        'allowed_values' => [
          'Buy' => 'Buy',
          'Sell' => 'Sell',
        ],
      ])
      ->setDefaultValue([
        [
          'value' => 'Buy',
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -18,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -18,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['volume'] = BaseFieldDefinition::create('decimal')
      ->setRevisionable(TRUE)
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Volume'))
      ->setDescription(t('The position Volume in lots'))
      ->setSetting('precision', 12)
      ->setSetting('scale', 2)
      ->setDefaultValue([0.01])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -17,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_decimal',
        'label' => 'above',
        'weight' => -17,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['symbol'] = BaseFieldDefinition::create('entity_reference')
      ->setTranslatable(FALSE)
      ->setLabel(t('Symbol'))
      ->setRequired(TRUE)
      ->setDescription(t('The position Trade Symbol'))
      ->setSettings([
        'target_type' => 'fxjournal_symbol',
        'allowed_values_function' => 'fxjournal_allowed_values_callback',
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'settings' => [
          'multiple_values' => FALSE,
        ],
        'weight' => -16,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -16,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['price_open'] = BaseFieldDefinition::create('decimal')
      ->setRevisionable(TRUE)
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Open price'))
      ->setDescription(t('The position Open Price'))
      ->setSetting('precision', 15)
      ->setSetting('scale', 5)
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_decimal',
        'label' => 'above',
        'weight' => -15,
        'settings' => [
          'scale' => 5,
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['stop_loss'] = BaseFieldDefinition::create('decimal')
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Stop Loss'))
      ->setDescription(t('The position Stop Loss price'))
      ->setSetting('precision', 15)
      ->setSetting('scale', 5)
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -14,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_decimal',
        'label' => 'above',
        'weight' => -14,
        'settings' => [
          'scale' => 5,
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['take_profit'] = BaseFieldDefinition::create('decimal')
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Take Profit'))
      ->setDescription(t('The position Take Profit price'))
      ->setSetting('precision', 15)
      ->setSetting('scale', 5)
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -13,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_decimal',
        'label' => 'above',
        'weight' => -13,
        'settings' => [
          'scale' => 5,
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['commission'] = BaseFieldDefinition::create('decimal')
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE)
      ->setRequired(TRUE)
      ->setLabel(t('Commission'))
      ->setDescription(t('The position Commission value'))
      ->setSetting('precision', 12)
      ->setSetting('scale', 2)
      ->setDefaultValue([0])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -12,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_decimal',
        'label' => 'above',
        'weight' => -12,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['close_datetime'] = BaseFieldDefinition::create('datetime')
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Close date'))
      ->setDescription(t('The position Close Date.'))
      ->setDisplayOptions('form', [
        'type' => 'datetime_default',
        'weight' => -11,
      ])
      ->setDisplayOptions('view', [
        'type' => 'datetime_default',
        'label' => 'above',
        'weight' => -11,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['price_close'] = BaseFieldDefinition::create('decimal')
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE)
      ->setLabel(t('Exit price'))
      ->setDescription(t('The position Close Price'))
      ->setSetting('precision', 15)
      ->setSetting('scale', 5)
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_decimal',
        'label' => 'above',
        'weight' => -10,
        'settings' => [
          'scale' => 5,
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['swap'] = BaseFieldDefinition::create('decimal')
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE)
      ->setRequired(TRUE)
      ->setLabel(t('Swap'))
      ->setDescription(t('The position Swap value'))
      ->setSetting('precision', 12)
      ->setSetting('scale', 2)
      ->setDefaultValue([0])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -9,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_decimal',
        'label' => 'above',
        'weight' => -9,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['profit'] = BaseFieldDefinition::create('decimal')
      ->setRevisionable(TRUE)
      ->setTranslatable(FALSE)
      ->setRequired(TRUE)
      ->setLabel(t('Profit'))
      ->setDescription(t('The position Profit'))
      ->setSetting('precision', 12)
      ->setSetting('scale', 2)
      ->setDefaultValue([0])
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
      ->setTranslatable(FALSE)
      ->setLabel(t('Author'))
      ->setDescription(t('The user ID of the forex journal record author.'))
      ->setSetting('target_type', 'user')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setTranslatable(FALSE)
      ->setDescription(t('The time that the forex journal record was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the forex journal record was last edited.'));

    return $fields;
  }

}
