<?php

declare(strict_types=1);

namespace Tests\GaBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

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
            // @see https://github.com/KnpLabs/knp-components/issues/160
            $request = Request::create($header->link()->getUri());
            $_GET = $request->query->all();

            $client->click($header->link());
            $this->assertStatusCode(200, $client);
        });
    }
}
