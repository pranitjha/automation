<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\user\Entity\User;

/**
 * Test case for editing user profiles.
 *
 * @group phptest
 */
class EditUserProfileTest extends BrowserTestBase {

  /**
   * The profile to install.
   *
   * @var string
   */
  protected $profile = 'standard';

  /**
   * A user with permission to administer users.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * The user whose profile will be edited.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $testUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create an administrator user with the permission to administer users.
    $this->adminUser = $this->drupalCreateUser(['administer users']);

    // Create a test user with no special permissions.
    $this->testUser = $this->drupalCreateUser();
  }

  /**
   * Tests editing a user profile.
   */
  public function testEditUserProfile() {
    // Log in as the administrator user.
    $this->drupalLogin($this->adminUser);

    // Go to the edit form of the test user.
    $this->drupalGet('/user/' . $this->testUser->id() . '/edit');

    // Verify that the edit form is accessible.
    $this->assertSession()->statusCodeEquals(200);

    // Define new values for the user profile.
    $edit = [
      'mail' => 'newemail@example.com',
      'pass[pass1]' => 'newpassword',
      'pass[pass2]' => 'newpassword',
    ];

    // Submit the form with the new values.
    $this->submitForm($edit, 'Save');

    // Reload the user from the database to check changes.
    $updatedUser = User::load($this->testUser->id());

    // Verify that the email has been updated.
    $this->assertEquals('newemail@example.com', $updatedUser->getEmail(), 'The user email has been updated.');

    // Verify that the password has been updated by attempting a login with the new password.
    $this->drupalLogout(); // Logout the admin user.
    $this->drupalLoginWithPassword($updatedUser->getAccountName(), 'newpassword');
    $this->assertSession()->pageTextContains("Member for");
  }

  /**
   * Logs in a user with the given username and password.
   *
   * @param string $username
   *   The username of the user to log in.
   * @param string $password
   *   The password of the user to log in.
   */
  protected function drupalLoginWithPassword($username, $password) {
    $edit = [
      'name' => $username,
      'pass' => $password,
    ];
    $this->drupalGet('user/login');
    $this->submitForm($edit, 'Log in');
  }

}
