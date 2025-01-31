<?php

declare(strict_types=1);

namespace Tests\GaBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class GroepenControllerTest extends WebTestCase
{
    public function testSort()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('ga_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', $this->getUrl('ga_groepen_index'));

        $headers = $crawler->filter('table.table thead tr th a');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) use ($client) {
            $client->click($header->link());
            $this->assertStatusCode(200, $client);
        });
    }

    public function testAdd()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('ga_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', $this->getUrl('ga_groepen_add'));
        $this->assertEquals(1, $crawler->selectLink('ErOpUit')->count());
        $this->assertEquals(1, $crawler->selectLink('Buurtmaatjes')->count());
        $this->assertEquals(1, $crawler->selectLink('Kwartiermaken')->count());
        $this->assertEquals(1, $crawler->selectLink('Buurtrestaurants')->count());
        $this->assertEquals(1, $crawler->selectLink('Organisatie')->count());

        $crawler = $client->click($crawler->selectLink('ErOpUit')->link());
        $this->assertStatusCode(200, $client);

        $form = $crawler->selectButton('groep[submit]')->form([
            'groep[naam]' => 'Testgroep',
        ]);
        $client->submit($form);
        $this->assertStatusCode(302, $client);
    }
}
