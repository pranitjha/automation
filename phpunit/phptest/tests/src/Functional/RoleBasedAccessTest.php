<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test login with different roles and verify access levels.
 *
 * @group phptest
 */
class RoleBasedAccessTest extends BrowserTestBase {

  /**
   * The profile to install.
   *
   * @var string
   */
  protected $profile = 'standard';

  /**
   * A user with the 'editor' role.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $editorUser;

  /**
   * A user with the 'viewer' role.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $viewerUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create users for each role.
    $this->editorUser = $this->createUser(['create article content', 'edit any article content'], 'content_viewer');
    $this->viewerUser = $this->createUser(['access content'], 'viewer');
  }

  /**
   * Tests access levels for users with different roles.
   */
  public function testAccessLevels() {
    // Test access for 'editor' user.
    $this->drupalLogin($this->editorUser);

    // Check if the 'editor' user has permission to create article content.
    $this->assertTrue($this->editorUser->hasPermission('create article content'), 'Editor user has permission to create articles.');

    // Attempt to access the article creation page.
    $this->drupalGet('/node/add/article');

    // Assert that the 'editor' user can access the article creation page.
    $this->assertSession()->statusCodeEquals(200, 'Editor user can access the article creation page.');

    // Log out the 'editor' user.
    $this->drupalLogout();

    // Test access for 'viewer' user.
    $this->drupalLogin($this->viewerUser);
    $this->drupalGet('/node/add/article');

    // Assert that the 'viewer' user cannot access the article creation page.
    $this->assertSession()->statusCodeEquals(403, 'Viewer user cannot access the article creation page.');

    // Log out the 'viewer' user.
    $this->drupalLogout();
  }

}
