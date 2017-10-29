<?php

namespace Drupal\products;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\products\Entity\ProductsInterface;

/**
 * Defines the storage handler class for products Entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * products Entity entities.
 *
 * @ingroup products
 */
interface ProductsStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of products Entity revision IDs for a specific products Entity.
   *
   * @param \Drupal\products\Entity\ProductsInterface $entity
   *   The products Entity.
   *
   * @return int[]
   *   products Entity revision IDs (in ascending order).
   */
  public function revisionIds(ProductsInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as products Entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   products Entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\products\Entity\ProductsInterface $entity
   *   The products Entity .
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ProductsInterface $entity);

  /**
   * Unsets the language for all products Entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
