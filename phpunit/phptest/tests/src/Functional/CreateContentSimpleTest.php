<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\node\Entity\Node;
use Drupal\Tests\BrowserTestBase;

/**
 * Test the creation and viewing of a page content type.
 *
 * @group mymodule
 */
class CreateContentSimpleTest extends BrowserTestBase {

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
   * The profile to install.
   *
   * @var string
   */
  protected $profile = 'standard';

  /**
   * A user with permission to create and view page content.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $authenticatedUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create a user with permissions to create and view page content.
    $this->authenticatedUser = $this->drupalCreateUser([
      'create article content',
      'view own article content',
    ]);
  }

  /**
   * Tests page content creation and viewing.
   */
  public function testPageContentCreationAndView() {
    // Log in the user.
    $this->drupalLogin($this->authenticatedUser);

    // Define the node details.
    $nodeDetails = [
      'type' => 'article',
      'title' => 'Test Page',
      'body' => [
        'value' => 'This is the body of the test page.',
        'format' => 'full_html',
      ],
      'status' => TRUE,
    ];

    // Create the node.
    $node = Node::create($nodeDetails);
    $node->save();

    // Check if the node is created successfully.
    $this->assertNotEmpty($node->id(), 'Page content has been created.');

    // Access the view page of the node.
    $this->drupalGet('node/' . $node->id());

    // Check that the title and body of the node appear on the page.
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($nodeDetails['title']);
    $this->assertSession()->pageTextContains($nodeDetails['body']['value']);
  }

}
