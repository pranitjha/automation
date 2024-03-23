<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\user\Entity\Role;

/**
 * Test the accessibility of the Article Title Resource REST endpoint.
 *
 * @group phptest
 */
class ArticleTitleResourceAccessTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'olivero';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'rest',
    'phptest',
  ];

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
  }

  /**
   * Test that the 'content_viewer' role can access the article titles and that anonymous users cannot.
   */
  public function testArticleTitleResourceAccessibility() {

    // Perform a request to the REST resource as an anonymous user.
    $response = $this->drupalGet('/phptest/article/titles', ['headers' => ['Accept' => 'application/json']]);
    $this->assertSession()->statusCodeEquals(403); // Expecting 'Access Denied' HTTP status code.

    // Create a user with the 'content_viewer' role.
    $contentViewerUser = $this->createUser(['view article titles']);
    $this->drupalLogin($contentViewerUser);

    // Perform a request to the REST resource as the content_viewer user.
    $response = $this->drupalGet('/phptest/article/titles', ['headers' => ['Accept' => 'application/json']]);
    $this->assertSession()->statusCodeEquals(200);
  }

}
