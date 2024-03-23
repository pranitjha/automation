<?php

namespace Drupal\Tests\LoginTest\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests login functionality.
 *
 * @group my_module
 */
class LoginTest extends BrowserTestBase {

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
  protected static $modules = ['user', 'phptest'];

  /**
   * A user with permission to access content overview page and administer content.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // Create a user with permission to access administration pages.
    $this->user = $this->drupalCreateUser(['access administration pages']);
  }

  /**
   * Tests user login.
   */
  public function testUserLogin() {
    // Access the login page.
    $this->drupalGet('user/login');

    // Submit the login form with the user credentials.
    $edit = [
      'name' => $this->user->getAccountName(),
      'pass' => $this->user->passRaw,
    ];
    $this->submitForm($edit, 'Log in');

    // Check the current user is redirected to their profile page after login.
    $this->assertSession()->addressEquals('user/' . $this->user->id());
  }

}
