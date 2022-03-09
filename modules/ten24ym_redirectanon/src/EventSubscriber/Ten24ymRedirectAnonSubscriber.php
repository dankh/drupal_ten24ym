<?php

namespace Drupal\ten24ym_redirectanon\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class Ten24ymRedirectAnonSubscriber implements EventSubscriberInterface {

  private \Drupal\Core\Session\AccountProxyInterface $account;

  /**
   * @var string
   */
  private $path;

  public function __construct() {
    $this->account = \Drupal::currentUser();
    $this->path = \Drupal::config('ten24ym_redirectanon.settings')->get('path');
  }

  public function checkAuthStatus(RequestEvent $event) {

    if ( empty($this->path)) {
      return;
    }

    $allowed = [
      'user.logout',
      'user.register',
      'user.login',
      'user.reset',
      'user.reset.form',
      'user.reset.login',
      'user.login.http',
      'user.logout.http',
      'user.pass'
    ];

    if (
      ($this->account->isAnonymous()) &&
        ( !in_array(\Drupal::routeMatch()->getRouteName(), $allowed)) &&
           \Drupal::request()->getRequestUri() !== $this->path
    ) {

      // add logic to check other routes you want available to anonymous users,
      // otherwise, redirect to login page.
      $route_name = \Drupal::routeMatch()->getRouteName();
      if (strpos($route_name, 'view') === 0 && strpos($route_name, 'rest_') !== FALSE) {
        return;
      }

      $response = new RedirectResponse($this->path, 307);
      $response->send();
    }
  }

  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('checkAuthStatus');

    return $events;
  }

}
