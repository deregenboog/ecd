<?php

namespace Tests\IzBundle\Controller;

use AppBundle\DataFixtures\AppFixtures;
use IzBundle\DataFixtures\IzFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class VrijwilligersControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->loadFixtures([
            AppFixtures::class,
            IzFixtures::class,
        ]);
    }

    public function testIndexSortColumns()
    {
        $client = $this->makeClient([
            'username' => 'admin',
            'password' => 'admin-password',
        ]);
        $crawler = $client->request('GET', '/iz/vrijwilligers/');

        $headers = $crawler->filter('table thead tr th a');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) use ($client) {
            // @see https://github.com/KnpLabs/knp-components/issues/160
            $request = Request::create($header->link()->getUri());
            $_GET = $request->query->all();

            $client->click($header->link());
            $this->assertStatusCode(200, $client);
            $_GET = [];
        });
    }

    public function testIndexFilter()
    {
        $client = $this->makeClient([
            'username' => 'admin',
            'password' => 'admin-password',
        ]);
        $crawler = $client->request('GET', '/iz/vrijwilligers/');

        $filter = $crawler->selectButton('iz_vrijwilliger_filter_filter')->form();
        $crawler = $client->submit($filter);

        $this->assertStatusCode(200, $client);
    }

    public function testIndexDownload()
    {
        $client = $this->makeClient([
            'username' => 'admin',
            'password' => 'admin-password',
        ]);
        $crawler = $client->request('GET', '/iz/vrijwilligers/');

        $filter = $crawler->selectButton('iz_vrijwilliger_filter_download')->form();
        $crawler = $client->submit($filter);

        $this->assertStatusCode(200, $client);
        $this->assertEquals(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $client->getResponse()->headers->get('content-type')
        );
        $this->assertRegExp(
            '/^attachment; filename="iz-vrijwilligers-\d{4}-\d{2}-\d{2}\.xlsx";$/',
            $client->getResponse()->headers->get('content-disposition')
        );
    }
}
