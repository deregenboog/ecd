<?php

namespace Tests\OdpBundle\Controller;

use AppBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class HuurverzoekenControllerTest extends WebTestCase
{
    public function testSortColumns()
    {
        $medewerker = $this->getContainer()->get(\AppBundle\Service\MedewerkerDao::class)->findByUsername('tw_user');
        $this->logIn($medewerker);

        $crawler = $this->client->request('GET', '/tw/huurverzoeken/');

        $headers = $crawler->filter('tr th a');
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
