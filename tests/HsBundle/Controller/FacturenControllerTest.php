<?php

declare(strict_types=1);

namespace Tests\HsBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class FacturenControllerTest extends WebTestCase
{
    public function testSortColumns()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('hs_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', '/hs/facturen/');
        $this->assertStatusCode(200, $client);

        $headers = $crawler->filter('tr th a.sortable');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) use ($client) {
            $client->click($header->link());
            $this->assertStatusCode(200, $client);
        });
    }
}
