<?php

namespace Drupal\products\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining products Entity entities.
 *
 * @ingroup products
 */
interface ProductsInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the products Entity name.
   *
   * @return string
   *   Name of the products Entity.
   */
  public function getName();

  /**
   * Sets the products Entity name.
   *
   * @param string $name
   *   The products Entity name.
   *
   * @return \Drupal\products\Entity\ProductsInterface
   *   The called products Entity.
   */
  public function setName($name);

  /**
   * Gets the  products Description.
   *
   * @return string_long
   *   Description of the products Entity.
   */
  public function getDescription();

  /**
   * Sets the products Entity Description.
   *
   * @param string $description
   *   The products Description.
   *
   * @return \Drupal\products\Entity\ProductsInterface
   *   The called products Entity.
   */
   public function setDescription($description);

   /**
    * Gets the products Entity price.
    *
    * @return int
    *   Creation price of the products Entity.
    */
   public function getPrice();

   /**
    * Sets the products Entity price.
    *
    * @param int $price
    *   The products Entity  price.
    *
    * @return \Drupal\products\Entity\ProductsInterface
    *   The called products Entity.
    */
   public function setPrice($price);

  /**
   * Gets the products Entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the products Entity.
   */
  public function getCreatedTime();

  /**
   * Sets the products Entity creation timestamp.
   *
   * @param int $timestamp
   *   The products Entity creation timestamp.
   *
   * @return \Drupal\products\Entity\ProductsInterface
   *   The called products Entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the products Entity published status indicator.
   *
   * Unpublished products Entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the products Entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a products Entity.
   *
   * @param bool $published
   *   TRUE to set this products Entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\products\Entity\ProductsInterface
   *   The called products Entity.
   */
  public function setPublished($published);

  /**
   * Gets the products Entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the products Entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\products\Entity\ProductsInterface
   *   The called products Entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the products Entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the products Entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\products\Entity\ProductsInterface
   *   The called products Entity.
   */
  public function setRevisionUserId($uid);

}
