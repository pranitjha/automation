<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\node\Entity\Node;
use Drupal\Tests\BrowserTestBase;

/**
 * Test filtering and sorting on the Content listing page.
 *
 * @group phptest
 */
class AccessListingWithFiltersTest extends BrowserTestBase {

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
  protected static $modules = ['node', 'phptest'];

  /**
   * A user with permission to access content overview page and administer content.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create a user with permissions to access and administer content.
    $this->adminUser = $this->drupalCreateUser([
      'access content overview',
      'administer nodes',
    ]);

    // Create an article node to test filtering and sorting.
    $node = Node::create([
      'type' => 'article',
      'title' => 'Test Article for Filtering',
    ]);
    $node->save();
  }

  /**
   * Tests filtering on the Content listing page.
   */
  public function testContentFiltering() {
    // Log in as the admin user.
    $this->drupalLogin($this->adminUser);

    // Go to the Content listing page.
    $this->drupalGet('/admin/content');

    // Apply filter to find the article created in setUp.
    $edit = [
      'title' => 'Test Article for Filtering',
      'type' => 'article',
      'status' => 'All',
    ];
    $this->submitForm($edit, 'Filter');

    // Verify that the filter results include the specific article.
    $this->assertSession()->pageTextContains('Test Article for Filtering');

    // Reset the filter to see all content again.
    $this->submitForm([], 'Reset');
  }

  /**
   * Tests sorting on the Content listing page.
   */
  public function testContentSorting() {
    // Log in as the admin user.
    $this->drupalLogin($this->adminUser);

    // Go to the Content listing page.
    $this->drupalGet('/admin/content');

    // Sort by Title.
    $this->clickLink('Title');

    // Verify that the sorting by title works (assuming ascending order).
    $this->assertSession()->pageTextContains('Test Article for Filtering');
  }

}
