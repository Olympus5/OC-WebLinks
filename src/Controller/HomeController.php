<?php

namespace WebLinks\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\Link;
use WebLinks\Form\Type\LinkType;

/**
 * @author Olympus5
 */
class HomeController {

  /**
   * Home page controller
   * @param app Silex application
   */
  public function indexAction(Application $app) {
    $links = $app['dao.link']->findAll();
    return $app['twig']->render('index.html.twig', array('links' => $links));
  }

  /**
   * Login page controller
   * @param request Incoming request
   * @param app Silex application
   */
  public function loginAction(Request $request, Application $app) {
    return $app['twig']->render('login.html.twig', array(
      'error' => $app['security.last_error']($request),
      'last_username' => $app['session']->get('_security.last_username')
    ));
  }

  /**
   * Add link controller
   * @param request Incoming request
   * @param app Silex application
   */
  public function addLinkAction(Request $request, Application $app) {
    $link = new Link();
    $linkForm = $app['form.factory']->create(LinkType::class, $link);

    $linkForm->handleRequest($request);

    if($linkForm->isSubmitted() && $linkForm->isValid()) {
      $user = $app['user'];
      $link->setAuthor($user);
      $app['dao.link']->save($link);
      $app['session']->getFlashBag()->add('success', 'The link was successfully submitted');
    }

    return $app['twig']->render('link_form.html.twig', array(
      'title' => 'New link',
      'linkForm' => $linkForm->createView()
    ));
  }
}
