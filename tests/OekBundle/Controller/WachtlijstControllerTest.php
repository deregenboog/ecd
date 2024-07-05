<?php

declare(strict_types=1);

namespace Tests\OekBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class WachtlijstControllerTest extends WebTestCase
{
    public function testSortColumns()
    {
        $this->markTestSkipped();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('oek_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', '/oek/wachtlijst/');
        $this->assertStatusCode(200, $client);

        $headers = $crawler->filter('tr th a.sortable');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) use ($client) {
            $client->click($header->link());
            $this->assertStatusCode(200, $client);
        });
    }
}
