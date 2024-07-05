<?php

declare(strict_types=1);

namespace Tests\GaBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class KlantdossiersControllerTest extends WebTestCase
{
    public function testSortColumns()
    {
        $this->markTestSkipped();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('ga_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', $this->getUrl('ga_klantdossiers_index'));

        $this->assertStatusCode(200, $client);
        $headers = $crawler->filter('table.table thead tr th a');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) use ($client) {
            $client->click($header->link());
            $this->assertStatusCode(200, $client);
        });
    }
}
