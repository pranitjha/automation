<?php

namespace Drupal\Tests\my_module\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Simple test to check that the site is working fine.
 *
 * @group my_module
 */
class SiteHealthCheckTest extends BrowserTestBase {

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
   * A simple test method to check site health.
   */
  public function testSiteHealth() {
    // Visit the front page.
    $this->drupalGet('<front>');

    // Check that the front page returns a 200 OK response.
    $this->assertSession()->statusCodeEquals(200);

    // Check for the existence of main structural elements.
    $this->assertSession()->elementExists('css', 'header');
    $this->assertSession()->elementExists('css', 'main');
  }

}
