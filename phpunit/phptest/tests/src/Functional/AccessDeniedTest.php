<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Check for 404 Not Found response.
 *
 * @group my_module
 */
class AccessDeniedTest extends BrowserTestBase {

  /**
   * The profile to install.
   *
   * @var string
   */
  protected $profile = 'standard';

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['phptest'];

  /**
   * A user with permission to access content overview page and administer content.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * Test a 404 Not Found response.
   */
  public function testNotFoundPage() {
    // Attempt to visit a non-existent page.
    $this->drupalGet('/admin');

    // Assert that a 404 status code is returned.
    $this->assertSession()->statusCodeEquals(403);
  }

}
