<?php

namespace WebLinks\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\Link;

/**
 * @author Olympus5
 */
class ApiController {

  /**
   * API links controller.
   * @param app Silex application
   * @return All links in JSON format
   */
  public function getLinksAction(Application $app) {
    $links = $app['dao.link']->findAll();

    $responseData = array();

    foreach ($links as $link) {
      $responseData[] = $this->buildArticleArray($link);
    }

    return $app->json($responseData);
  }

  /**
   * API link details controller.
   * @param id The link id
   * @param app Silex application
   * @return Link details in JSON format
   */
  public function getLinkAction($id, Application $app) {
    $link = $app['dao.link']->find($id);

    $responseData = $this->buildArticleArray($link);

    return $app->json($responseData);
  }

  /**
   * Convert a Link object into associative array for JSON encoding
   * @param link Link object
   * @return Associative array whose fields are the link properties
   */
  private function buildArticleArray(Link $link) {
    $data = array(
      'id' => $link->getId(),
      'title' => $link->getTitle(),
      'url' => $link->getUrl()
    );

    return $data;
  }
}
