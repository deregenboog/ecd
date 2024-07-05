<?php

declare(strict_types=1);

namespace Tests\DagbestedingBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class TrajectenControllerTest extends WebTestCase
{
    public function testSortColumns()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('dagbesteding_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', '/dagbesteding/trajecten/');
        $this->assertStatusCode(200, $client);

        $headers = $crawler->filter('tr th a.sortable');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) use ($client) {
            $client->click($header->link());
            $this->assertStatusCode(200, $client);
        });
    }
}
