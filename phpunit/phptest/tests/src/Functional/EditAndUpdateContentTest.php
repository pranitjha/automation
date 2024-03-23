<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\node\Entity\Node;
use Drupal\Tests\BrowserTestBase;

/**
 * Test the updating of an article content type and reflecting changes on the frontend.
 *
 * @group phptest
 */
class EditAndUpdateContentTest extends BrowserTestBase {

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
   * A user with permission to edit and view content.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $editorUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create a user with permissions to edit and view article content.
    $this->editorUser = $this->drupalCreateUser([
      'edit any article content',
      'view any article content',
    ]);

    // Create an article node to edit.
    $node = Node::create([
      'type' => 'article',
      'title' => 'Original Article Title',
      'body' => [
        'value' => 'Original article body text',
        'format' => 'plain_text',
      ],
    ]);
    $node->save();
  }

  /**
   * Tests article content update and frontend reflection.
   */
  public function testArticleContentUpdate() {
    // Log in the editor user.
    $this->drupalLogin($this->editorUser);

    // Load the article node created in setUp for editing.
    $node = $this->drupalGetNodeByTitle('Original Article Title');
    $this->drupalGet('node/' . $node->id() . '/edit');

    // Change some of the field values.
    $newTitle = 'Updated Article Title';
    $newBody = 'Updated article body text';
    $edit = [
      'title[0][value]' => $newTitle,
      'body[0][value]' => $newBody,
    ];
    $this->submitForm($edit, 'Save');

    // Verify the changes are reflecting on the frontend.
    $this->drupalGet('node/' . $node->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($newTitle);
    $this->assertSession()->pageTextContains($newBody);
  }

}
