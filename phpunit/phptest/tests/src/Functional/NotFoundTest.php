<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Check for 404 Not Found response.
 *
 * @group my_module
 */
class NotFoundTest extends BrowserTestBase {

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
   * Test a 404 Not Found response.
   */
  public function testNotFoundPage() {
    // Attempt to visit a non-existent page.
    $this->drupalGet('/404-path');

    // Assert that a 404 status code is returned.
    $this->assertSession()->statusCodeEquals(404);
  }

}
