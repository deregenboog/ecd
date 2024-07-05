<?php

declare(strict_types=1);

namespace Tests\OekBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DeelnemersControllerTest extends WebTestCase
{
    public function testSortColumns()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('oek_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', '/oek/deelnemers/');
        $this->assertStatusCode(200, $client);

        $headers = $crawler->filter('tr th a.sortable');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) use ($client) {
            $client->click($header->link());
            $this->assertStatusCode(200, $client);
        });
    }
}
