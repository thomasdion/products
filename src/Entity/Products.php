<?php

namespace Drupal\products\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the products Entity.
 *
 * @ingroup products
 *
 * @ContentEntityType(
 *   id = "products",
 *   label = @Translation("products Entity"),
 *   handlers = {
 *     "storage" = "Drupal\products\ProductsStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\products\ProductsListBuilder",
 *     "views_data" = "Drupal\products\Entity\ProductsViewsData",
 *     "translation" = "Drupal\products\ProductsTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\products\Form\ProductsForm",
 *       "add" = "Drupal\products\Form\ProductsForm",
 *       "edit" = "Drupal\products\Form\ProductsForm",
 *       "delete" = "Drupal\products\Form\ProductsDeleteForm",
 *     },
 *     "access" = "Drupal\products\ProductsAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\products\ProductsHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "products",
 *   data_table = "products_field_data",
 *   revision_table = "products_revision",
 *   revision_data_table = "products_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer products Entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/products/{products}",
 *     "add-form" = "/admin/structure/products/add",
 *     "edit-form" = "/admin/structure/products/{products}/edit",
 *     "delete-form" = "/admin/structure/products/{products}/delete",
 *     "version-history" = "/admin/structure/products/{products}/revisions",
 *     "revision" = "/admin/structure/products/{products}/revisions/{products_revision}/view",
 *     "revision_revert" = "/admin/structure/products/{products}/revisions/{products_revision}/revert",
 *     "revision_delete" = "/admin/structure/products/{products}/revisions/{products_revision}/delete",
 *     "translation_revert" = "/admin/structure/products/{products}/revisions/{products_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/products",
 *   },
 *   field_ui_base_route = "products.settings"
 * )
 */
class Products extends RevisionableContentEntityBase implements ProductsInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the products owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

    /**
     * {@inheritdoc}
     */
    public function getDescription() {
      return $this->get('description')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description) {
      $this->set('description', $description);
      return $this;
    }

   /**
   * {@inheritdoc}
   */
   public function getPrice() {
     return $this->get('price')->value;
   }

   /**
   * {@inheritdoc}
   */
   public function setPrice($price){
     $this->set('price', $price);
   }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the products Entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the products Entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

      $fields['description'] = BaseFieldDefinition::create('string_long')
        ->setLabel(t('Products Description'))
        ->setDescription(t('This is the products description.'))
        ->setRequired(TRUE)
        ->setRevisionable(TRUE)
        ->setDefaultValue('')
        ->setDisplayOptions('form', [
          'type'     => 'string_textarea',
          'weight'   => -1,
          'settings' => ['rows' => 25,]
        ])
        ->setDisplayConfigurable('form', TRUE);

        $fields['price'] = BaseFieldDefinition::create('integer')
          ->setLabel(t('Products price'))
          ->setDescription(t('This is the products price.'))
          ->setRequired(TRUE)
          ->setRevisionable(TRUE)
          ->setDefaultValue(0)
          ->setDisplayOptions('form', [
            'type'     => 'integer',
            'weight'   => -2,
            'max_length' => 4,
          ]);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the products Entity is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
