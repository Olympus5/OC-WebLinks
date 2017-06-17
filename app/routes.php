<?php

use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\Link;
use WebLinks\Form\Type\LinkType;
use WebLinks\Domain\User;
use WebLinks\Form\Type\UserType;

// Home page
$app->get('/', '\WebLinks\Controller\HomeController::indexAction')->bind('home');

//Login page
$app->get('/login', '\WebLinks\Controller\HomeController::loginAction')->bind('login');

//Add new link
$app->match('/link/add', '\WebLinks\Controller\HomeController::addLinkAction')->bind('link_add');

//Admin page
$app->get('/admin', "\WebLinks\Controller\AdminController::indexAction")->bind('admin');

//Admin add new link
$app->match('/admin/link/add', '\WebLinks\Controller\HomeController::addLinkAction')->bind('admin_link_add');

//Edit link
$app->match('/admin/link/{id}/edit', '\WebLinks\Controller\AdminController::editLinkAction')->bind('admin_link_edit');

//Delete link
$app->get('/admin/link/{id}/delete', '\WebLinks\Controller\AdminController::deleteLinkAction')->bind('admin_link_delete');

//Add user
$app->match('/admin/user/add', '\WebLinks\Controller\AdminController::addUserAction')->bind('admin_user_add');

//Edit user
$app->match('/admin/user/{id}/edit', '\WebLinks\Controller\AdminController::editUserAction')->bind('admin_user_edit');

//Delete user
$app->get('/admin/user/{id}/delete', '\WebLinks\Controller\AdminController::deleteUserAction')->bind('admin_user_delete');

//API: get all links
$app->get('/api/links', '\WebLinks\Controller\ApiController::getLinksAction')->bind('api_links');

//API: get a link
$app->get('/api/link/{id}', '\WebLinks\Controller\ApiController::getLinkAction')->bind('api_link');
