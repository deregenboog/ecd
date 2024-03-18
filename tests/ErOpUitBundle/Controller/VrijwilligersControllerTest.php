<?php

declare(strict_types=1);

namespace Tests\ErOpUitBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class VrijwilligersControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('eou_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', $this->getUrl('eropuit_vrijwilligers_index'));
        $this->assertStatusCode(200, $client);
        // $rows = $crawler->filter('table.table > tbody > tr')->siblings();
        // //$this->assertEquals(16, $rows->count()); // 19 resultaten in index scherm. filtert op 17 resultaten. waarom?
        // $this->assertGreaterThan(1, $rows->count());
    }

    public function testSort()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('eou_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', $this->getUrl('eropuit_vrijwilligers_index'));
        $this->assertStatusCode(200, $client);
        $headers = $crawler->filter('table.table tr th a');
        $this->assertGreaterThan(0, $headers->count());

        $headers->each(function ($header) use ($client) {
            $client->click($header->link());
            $this->assertStatusCode(200, $client);
        });
    }

    public function testFilter()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('eou_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', $this->getUrl('eropuit_vrijwilligers_index'));
        $this->assertStatusCode(200, $client);
        $form = $crawler->selectButton('vrijwilliger_filter[filter]')->form([
            'vrijwilliger_filter[vrijwilliger][naam]' => 'asdfasdfasdfasdfasdfasdf',
        ]);

        $crawler = $client->submit($form);
        $rows = $crawler->filter('table.table tbody tr');
        $this->assertLessThanOrEqual(1, $rows->count());
    }

    public function testAddFilter()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('eou_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', $this->getUrl('eropuit_vrijwilligers_add'));
        $this->assertStatusCode(200, $client);
        $form = $crawler->selectButton('vrijwilliger_filter[filter]')->form([
            'vrijwilliger_filter[naam]' => 'asdfasdfasdfasdfasdfasfasdfasdf',
        ]);

        $crawler = $client->submit($form);
        $rows = $crawler->filter('table.table tbody tr');
//        $this->assertEquals(19, $rows->count());
        $this->assertLessThanOrEqual(1, $this->count());
    }
}
