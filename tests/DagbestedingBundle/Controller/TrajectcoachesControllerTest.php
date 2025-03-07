<?php

declare(strict_types=1);

namespace Tests\DagbestedingBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class TrajectcoachesControllerTest extends WebTestCase
{
    public function testUserHasNoAccess()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('dagbesteding_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', '/dagbesteding/admin/trajectcoaches/');
        $this->assertStatusCode(403, $client);
    }

    public function testAdminHasAccess()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('dagbesteding_admin');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', '/dagbesteding/admin/trajectcoaches/');
        $this->assertStatusCode(200, $client);
    }

    public function testSortColumns()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('dagbesteding_admin');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', '/dagbesteding/admin/trajectcoaches/');
        $this->assertStatusCode(200, $client);

        $headers = $crawler->filter('tr th a.sortable');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) use ($client) {
            $client->click($header->link());
            $this->assertStatusCode(200, $client);
        });
    }
}
