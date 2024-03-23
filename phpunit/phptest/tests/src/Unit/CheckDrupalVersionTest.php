<?php

namespace Drupal\Tests\phptest\Unit;

use Drupal\Tests\UnitTestCase;

/**
 * Test case for checking the Drupal version.
 *
 * @group phptest
 */
class CheckDrupalVersionTest extends UnitTestCase {

  /**
   * Tests the Drupal version.
   */
  public function testDrupalVersion() {
    // Get the Drupal version.
    $version = \Drupal::VERSION;

    // Check if the version starts with "10".
    $this->assertStringStartsWith('10', $version, 'The Drupal version is 10.x.');
  }

}
