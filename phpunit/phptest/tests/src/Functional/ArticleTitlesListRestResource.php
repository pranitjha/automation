<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\node\Entity\Node;
use Drupal\Tests\BrowserTestBase;
use Drupal\user\Entity\Role;

/**
 * Tests the article titles REST resource.
 *
 * @group phptest
 */
class ArticleTitlesListRestResource extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'olivero';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'user',
    'system',
    'rest',
    'serialization',
    'phptest',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Add the 'view article titles' permission to 'content_viewer' role if not already present.
    $contentViewerRole = Role::load('content_viewer') ?: Role::create(['id' => 'content_viewer', 'label' => 'Content Viewer']);
    $contentViewerRole->grantPermission('view article titles');
    $contentViewerRole->save();
  }

  /**
   * Test the REST resource for fetching article titles.
   */
  public function testArticleTitlesRestResource() {
    // Create a user with the 'content_viewer' role.
    $contentViewerUser = $this->createUser(['view article titles']);

    // Create several article nodes.
    $titles = [];
    for ($i = 1; $i <= 5; $i++) {
      $title = "Article $i";
      $titles[] = $title;
      $node = Node::create([
        'type' => 'article',
        'title' => $title,
        'uid' => $contentViewerUser->id(),
        'status' => 1,
      ]);
      $node->save();
    }

    // Log in as the content_viewer user.
    $this->drupalLogin($contentViewerUser);

    // Perform a request to the REST resource and parse the JSON response.
    $response = $this->drupalGet('/phptest/article/titles', [
      'headers' => ['Accept' => 'application/json']
    ]);
    $json_response = json_decode($response, TRUE);

    // Ensure that the response status code is 200 and content type is JSON.
    $this->assertSession()->statusCodeEquals(200);
    $this->assertTrue($this->getSession()->getResponseHeader('content-type') === 'application/json');

    // Extract titles from the JSON response.
    $response_titles = array_map(function ($content) {
      return $content['title'];
    }, $json_response);

    // Assert that all created article titles are present in the response.
    foreach ($titles as $title) {
      $this->assertContains($title, $response_titles, 'The REST response contains the article title.');
    }
  }

}
