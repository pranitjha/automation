<?php

namespace Drupal\phptest\Utility;

/**
 * My custom service class.
 */
class PhpTestUtil {

  /**
   * Returns fibonacci series upto n terms.
   *
   * @param int $n
   *   Number of terms.
   *
   * @return string
   *   Fibonacci series.
   */
  public function fibonacciSeries(int $n): string {
    $fibonacci = [0, 1];
    for ($i = 2; $i < $n; $i++) {
      $fibonacci[] = $fibonacci[$i - 1] + $fibonacci[$i - 2];
    }

    return implode(', ', $fibonacci);
  }

}
