<?php

namespace Drupal\smile_test\Access;

use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Class FilterPermissions.
 *
 * @package Drupal\smile_test\Access
 */
class FilterPermissions implements AccessInterface {

  /**
   * Access for authenticated users only.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   One of the required parameters.
   *
   * @return \Drupal\Core\Access\AccessResultAllowed|\Drupal\Core\Access\AccessResultForbidden
   *   Return access type.
   */

    public function access(AccountInterface $account) {
    if (!in_array('authenticated', $account->getRoles())) {
      return AccessResult::forbidden();
    }
    return AccessResult::allowed();
  }

}
