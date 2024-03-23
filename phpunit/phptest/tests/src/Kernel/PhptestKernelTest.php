<?php

namespace Drupal\Tests\phptest\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Tests the functionality of the RouteSubscriber.
 *
 * @group custom_module
 */
class PhptestKernelTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['phptest'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Additional setup if needed.
  }

  /**
   * Tests the functionality of the RouteSubscriber.
   */
  public function testRouteSubscriber(): void {
    // Create a request for the "/redirect" path.
    $request = Request::create('/redirect', 'GET');

    // Process the request.
    $response = $this->container->get('http_kernel')->handle($request);

    // Check that the response is a redirect to the home page.
    $this->assertEquals(302, $response->getStatusCode());
    $this->assertEquals('/', $response->headers->get('Location'));
  }

}
