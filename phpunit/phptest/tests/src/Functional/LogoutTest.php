<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the logout functionality.
 *
 * @group phptest
 */
class LogoutTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['user'];

  /**
   * A simple user to use for testing.
   */
  private $user;

  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // Create a user for testing.
    $this->user = $this->drupalCreateUser(['access content']);
  }

  /**
   * Tests logout functionality.
   */
  public function testLogout() {
    // Log in the user.
    $this->drupalLogin($this->user);

    // Go to the logout URL.
    $this->drupalGet('user/logout');

    // Go to the logout URL.
    $this->drupalGet('user');

    // Redirect to user/login confirming logout.
    $this->assertSession()->addressEquals('user/login');

    // Check for the user login form which shows up after logged out.
    $this->assertSession()->fieldExists('name');
  }

}
