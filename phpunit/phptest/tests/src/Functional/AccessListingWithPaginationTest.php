<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\node\Entity\Node;
use Drupal\Tests\BrowserTestBase;

/**
 * Test the pagination on the Content listing page.
 *
 * @group phptest
 */
class AccessListingWithPaginationTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['node', 'phptest'];

  /**
   * The default theme to use for the test.
   *
   * @var string
   */
  protected $defaultTheme = 'stark';

  /**
   * A user with permission to access content overview page.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * The profile to install.
   *
   * @var string
   */
  protected $profile = 'standard';

  /**
   * The number of nodes to create for testing pagination.
   *
   * @var int
   */
  protected $nodeCount = 55;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create a user with permission to access the content overview page.
    $this->adminUser = $this->drupalCreateUser(['access content overview']);

    // Create more than 10 article nodes to test pagination.
    for ($i = 0; $i < $this->nodeCount; $i++) {
      $node = Node::create([
        'type' => 'article',
        'title' => 'Article ' . $i,
      ]);
      $node->save();
    }
  }

  /**
   * Tests the pagination on the Content listing page.
   */
  public function testContentPagination() {
    // Log in as the admin user.
    $this->drupalLogin($this->adminUser);

    // Go to the Content listing page.
    $this->drupalGet('/admin/content');

    // Verify that the first page of content is displayed.
    $this->assertSession()->statusCodeEquals(200);

    // Asc sort by title.
    $link = $this->getSession()->getPage()->find('css', 'a[title="sort by Title"]');
    $link->click();

    // "Article 1" should be on the first page.
    $this->assertSession()->pageTextContains('Article 1');

    // Use pagination to go to the next page.
    $link = $this->getSession()->getPage()->find('css', 'a[rel="next"]');
    $link->click();

    // Verify that the second page of content is displayed.
    // Now "Article 1" should not be present on the second page.
    $this->assertSession()->pageTextNotContains('Article 1');
    // Check for the presence of "Article 54" or another article that should be on the second page.
    $this->assertSession()->pageTextContains('Article 54');
    $this->assertSession()->linkExists('Previous');
  }

}
