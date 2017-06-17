<?php

namespace WebLinks\Tests;

require_once __DIR__.'/../../vendor/autoload.php';

use Silex\WebTestCase;

/**
 * @author Olympus5
 */
class AppTest extends WebTestCase {

  /**
     * Basic, application-wide functional test.
     * Simply checks that all application URLs load successfully.
     * During test execution, this method is called for each URL returned by the provideUrls method.
     * @dataProvider provideUrls
     */
  public function testPageIsSuccessfull($url) {
    $client = $this->createClient();
    $client->request('GET', $url);

    $this->assertTrue($client->getResponse()->isSuccessful());
  }

  /**
   * @inheritDoc
   */
  public function createApplication() {
    $app = new \Silex\Application();

    require __DIR__.'/../../app/config/dev.php';
    require __DIR__.'/../../app/app.php';
    require __DIR__.'/../../app/routes.php';

    unset($app['exception_handler']);

    $app['session.test'] = true;

    $app['security.access_rules'] = array();

    return $app;
  }

  public function provideUrls() {
    return array(
      array('/'),
      array('/login'),
      array('/link/add'),
      array('/admin'),
      array('/admin/link/add'),
      array('/admin/link/1/edit'),
      array('/admin/user/add'),
      array('/admin/user/1/edit')
    );
  }

}
