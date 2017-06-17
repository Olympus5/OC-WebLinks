<?php

namespace WebLinks\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\Link;
use WebLinks\Form\Type\LinkType;
use WebLinks\Domain\User;
use WebLinks\Form\Type\UserType;

/**
 * @author Olympus5
 */
class AdminController {

  /**
   * Admin page controller
   * @param app Silex application
   */
  public function indexAction(Application $app) {
    $links = $app['dao.link']->findAll();
    $users = $app['dao.user']->findAll();

    return $app['twig']->render('admin.html.twig', array(
      'links' => $links,
      'users' => $users
    ));
  }

  /**
   * Edit link controller
   * @param id The link id
   * @param request Incoming request
   * @param app Silex application
   */
  public function editLinkAction($id, Request $request, Application $app) {
    $link = $app['dao.link']->find($id);
    $linkForm = $app['form.factory']->create(LinkType::class, $link);

    $linkForm->handleRequest($request);

    if($linkForm->isSubmitted() && $linkForm->isValid()) {
      $app['dao.link']->save($link);
      $app['session']->getFlashBag()->add('success', 'The link was successfully updated.');
    }

    return $app['twig']->render('link_form.html.twig', array(
      'title' => 'Edit link',
      'linkForm' => $linkForm->createView()
    ));
  }

  /**
   * Delete link controller
   * @param id The link id
   * @param app Silex application
   */
  public function deleteLinkAction($id, Application $app) {
    $app['dao.link']->delete($id);
    $app['session']->getFlashBag()->add('success', 'The link was successfully removed.');

    return $app->redirect($app['url_generator']->generate('admin'));
  }

  /**
   * Add user controller
   * @param request Incoming request
   * @param app Silex application
   */
  public function addUserAction(Request $request, Application $app) {
    $user = new User();
    $userForm = $app['form.factory']->create(UserType::class, $user);

    $userForm->handleRequest($request);

    if($userForm->isSubmitted() && $userForm->isValid()) {
      $salt = substr(md5(time()), 0, 23);
      $user->setSalt($salt);
      $plainPassword = $user->getPassword();

      $encoder = $app['security.encoder.bcrypt'];

      $password = $encoder->encodePassword($plainPassword, $user->getSalt());
      $user->setPassword($password);
      $app['dao.user']->save($user);
      $app['session']->getFlashBag()->add('success', 'The user was successfully created.');
    }

    return $app['twig']->render('user_form.html.twig', array(
      'title' => 'New user',
      'userForm' => $userForm->createView()
    ));
  }

  /**
   * Edit user controller
   * @param id The user id
   * @param request Incoming request
   * @param app Silex application
   */
  public function editUserAction($id, Request $request, Application $app) {
    $user = $app['dao.user']->find($id);
    $userForm = $app['form.factory']->create(UserType::class, $user);

    $userForm->handleRequest($request);

    if($userForm->isSubmitted() && $userForm->isValid()) {
      $plainPassword = $user->getPassword();

      $encoder = $app['security.encoder_factory']->getEncoder($user);

      $password = $encoder->encodePassword($plainPassword, $user->getSalt());
      $user->setPassword($password);
      $app['dao.user']->save($user);
      $app['session']->getFlashBag()->add('success', 'The user was successfully updated.');
    }

    return $app['twig']->render('user_form.html.twig', array(
      'title' => 'Edit user',
      'userForm' => $userForm->createView()
    ));
  }

  /**
   * Delete user controller
   * @param id The user id
   * @param app Silex application
   */
  public function deleteUserAction($id, Application $app) {
    $app['dao.link']->deleteAllByUser($id);
    $app['dao.user']->delete($id);

    $app['session']->getFlashBag()->add('success', 'The user was successfully removed.');

    return $app->redirect($app['url_generator']->generate('admin'));
  }
}
