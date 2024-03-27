<?php

namespace Drupal\Tests\phptest\Unit;

use Drupal\phptest\Utility\PhpTestUtil;
use Drupal\Tests\UnitTestCase;

/**
 * Tests the PhpTestUtil class.
 *
 * @group phptest
 */
class PhpTestUtilTest extends UnitTestCase {

  /**
   * Tests the fibonacciSeries method.
   */
  public function testFibonacciSeries(): void {
    $phpTestUtil = new PhpTestUtil();

    // Test with 5 terms.
    $this->assertEquals('0, 1, 1, 2, 3', $phpTestUtil->fibonacciSeries(5));

    // Test with 7 terms.
    $this->assertEquals('0, 1, 1, 2, 3, 5, 8', $phpTestUtil->fibonacciSeries(7));

  }

}
