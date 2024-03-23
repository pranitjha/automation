<?php

namespace Drupal\phptest\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Redirects /redirect to the home page.
 */
class PhpTestRouteSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onKernelResponse'];
    return $events;
  }

  /**
   * Redirects /redirect to the home page.
   *
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *   The event to process.
   */
  public function onKernelResponse(ResponseEvent $event): void {
    $request = $event->getRequest();

    // Check if the path is /redirect.
    if ($request->getPathInfo() === '/redirect') {
      // Create a RedirectResponse to the home page.
      $response = new RedirectResponse('/');
      // Set the response for the event.
      $event->setResponse($response);
    }

  }

}
