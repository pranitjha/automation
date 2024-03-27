<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\node\Entity\Node;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the article search view with exposed filter functionality.
 *
 * @group phptest
 */
class ArticleSearchInViewTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'olivero';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['node', 'user', 'block', 'views', 'phptest'];

  /**
   * The admin user.
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
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create an admin user with permission to create articles.
    $this->adminUser = $this->drupalCreateUser([
      'administer nodes',
      'administer content types',
      'access content overview',
    ]);
  }

  /**
   * Tests creating an article and searching for it in the view.
   */
  public function testArticleSearch(): void {
    // Login as admin user.
    $this->drupalLogin($this->adminUser);

    // Create an article node with a unique title.
    $title = 'Unique Article Title ' . time();
    $node = Node::create([
      'type'  => 'article',
      'title' => $title,
    ]);
    $node->save();

    // Visit the view page.
    $this->drupalGet('admin/content');
    $this->assertSession()->statusCodeEquals(200);

    // Use the exposed form to filter by node title.
    $edit = [
      'title' => $title, // Here 'title' is the identifier of the filter form field, adjust as needed.
    ];
    $this->submitForm($edit, 'Filter'); // The value for 'Apply' button text may vary.

    // Check if the newly created node is listed on the page after filtering.
    $this->assertSession()->pageTextContains($title);
  }

}
