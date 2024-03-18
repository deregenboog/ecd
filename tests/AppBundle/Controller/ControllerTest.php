<?php

declare(strict_types=1);

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class ControllerTest extends WebTestCase
{
    public function testIndexPages()
    {
        $client = static::createClient();

        $admin = static::getContainer()
            ->get(\AppBundle\Service\MedewerkerDao::class)
            ->findByUsername('admin');
        $client->loginUser($admin);

        foreach ($this->getPaths() as $path) {
            $client->request(Request::METHOD_GET, $path);
            if (302 === $client->getResponse()->getStatusCode()) {
                // ignore redirects
                continue;
            }
            $this->assertResponseIsSuccessful();
        }
    }

    public function testSortColumns()
    {
        $client = static::createClient();

        $admin = static::getContainer()
            ->get(\AppBundle\Service\MedewerkerDao::class)
            ->findByUsername('admin');
        $client->loginUser($admin);

        foreach ($this->getPaths() as $path) {
            $crawler = $client->request(Request::METHOD_GET, $path);
            if (302 === $client->getResponse()->getStatusCode()) {
                // ignore redirects
                continue;
            }

            $headers = $crawler->filter('tr th a.sortable');
            $headers->each(function ($header) use ($client) {
                $client->click($header->link());
                $this->assertResponseIsSuccessful();
            });
        }
    }

    private function getPaths()
    {
        $routes = static::getContainer()->get('router')->getRouteCollection();
        foreach ($routes as $name => $route) {
            /**
             * @var string $name
             * @var Route  $route
             */
            if (preg_match('/^[a-z]+_[a-z]+_index$/', $name)
                && false === strpos($route->getPath(), '{')
                && ([] == $route->getMethods() || in_array(Request::METHOD_GET, $route->getMethods()))
            ) {
                yield $route->getPath();
            }
        }
    }
}
