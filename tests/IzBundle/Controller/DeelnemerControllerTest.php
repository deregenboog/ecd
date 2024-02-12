<?php

namespace Tests\IzBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class DeelnemerControllerTest extends WebTestCase
{
    public function testSortColumns()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('iz_user');

        $client->loginUser($medewerker);

        $crawler = $client->request('GET', '/iz/klanten/');

        $this->assertStatusCode(200, $client);

        $headers = $crawler->filter('tr th a');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) use ($client) {
            // @see https://github.com/KnpLabs/knp-components/issues/160
            $request = Request::create($header->link()->getUri());
            $_GET = $request->query->all();

            $client->click($header->link());
            $this->assertStatusCode(200, $client);
        });
    }
}
