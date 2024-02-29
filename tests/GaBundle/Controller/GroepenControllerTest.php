<?php

namespace Tests\GaBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use AppBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class GroepenControllerTest extends WebTestCase
{
    public function testSort()
    {
        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('ga_user');
        $this->client->loginUser($medewerker);

        $crawler = $this->client->request('GET', $this->getUrl('ga_groepen_index'));

        $headers = $crawler->filter('table.table thead tr th a');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) {
            // @see https://github.com/KnpLabs/knp-components/issues/160
            $request = Request::create($header->link()->getUri());
            $_GET = $request->query->all();

            $this->client->click($header->link());
            $this->assertStatusCode(200, $this->client);
        });
    }

    public function testAdd()
    {
        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('ga_user');
        $this->client->loginUser($medewerker);

        $crawler = $this->client->request('GET', $this->getUrl('ga_groepen_add'));
        $this->assertEquals(1, $crawler->selectLink('ErOpUit')->count());
        $this->assertEquals(1, $crawler->selectLink('Buurtmaatjes')->count());
        $this->assertEquals(1, $crawler->selectLink('Kwartiermaken')->count());
        $this->assertEquals(1, $crawler->selectLink('OpenHuis')->count());
        $this->assertEquals(1, $crawler->selectLink('Organisatie')->count());

        $crawler = $this->client->click($crawler->selectLink('ErOpUit')->link());
        $this->assertStatusCode(200, $this->client);

        $form = $crawler->selectButton('groep[submit]')->form([
            'groep[naam]' => 'Testgroep',
        ]);
        $this->client->submit($form);
        $this->assertStatusCode(302, $this->client);
    }
}
