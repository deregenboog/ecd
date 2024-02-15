<?php

namespace Tests\TwBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use AppBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class HuuraanbiedingenControllerTest extends WebTestCase
{
    public function testSortColumns()
    {
        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('tw_user');
        $this->client->loginUser($medewerker);

        $crawler = $this->client->request('GET', '/tw/huuraanbiedingen/');
        $this->assertStatusCode(200, $this->client);

        $headers = $crawler->filter('tr th a.sortable');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) {
            // @see https://github.com/KnpLabs/knp-components/issues/160
            $request = Request::create($header->link()->getUri());
            $_GET = $request->query->all();

            $this->client->click($header->link());
            $this->assertStatusCode(200, $this->client);
        });
    }
}
