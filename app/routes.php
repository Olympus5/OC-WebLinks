<?php

use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\Link;
use WebLinks\Form\Type\LinkType;

// Home page
$app->get('/', function () use ($app) {
    $links = $app['dao.link']->findAll();
    return $app['twig']->render('index.html.twig', array('links' => $links));
})->bind('home');

//Login page
$app->get('/login', function(Request $request) use ($app) {
  return $app['twig']->render('login.html.twig', array(
    'error' => $app['security.last_error']($request),
    'last_username' => $app['session']->get('_security.last_username')
  ));
})->bind('login');

//Link page
$app->match('/link/add', function(Request $request) use ($app) {
  $link = new Link();
  $linkForm = $app['form.factory']->create(LinkType::class, $link);

  $user = $app['user'];
  $link->setAuthor($user);

  $linkForm->handleRequest($request);

  if($linkForm->isSubmitted() && $linkForm->isValid()) {
    $app['dao.link']->save($link);
    $app['session']->getFlashBag()->add('success', 'The link was successfully submitted');
  }

  return $app['twig']->render('link_form.html.twig', array(
    'title' => 'New link',
    'linkForm' => $linkForm->createView()
  ));
})->bind('link_add');
