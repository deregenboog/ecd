<?php

declare(strict_types=1);

namespace Tests\DagbestedingBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use DagbestedingBundle\Entity\Afsluiting;
use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DeelnemersControllerTest extends WebTestCase
{
    public function testSortColumns()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()
            ->get(MedewerkerDao::class)
            ->findByUsername('dagbesteding_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', '/dagbesteding/deelnemers/');
        $this->assertStatusCode(200, $client);

        $headers = $crawler->filter('tr th a.sortable');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) use ($client) {
            $client->click($header->link());
            $this->assertStatusCode(200, $client);
        });
    }

    public function testAddDeelnemer()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()
            ->get(MedewerkerDao::class)
            ->findByUsername('dagbesteding_user');
        $client->loginUser($medewerker);

        // request deelnemers list
        $crawler = $client->request('GET', '/dagbesteding/deelnemers/');
        $this->assertResponseIsSuccessful();

        // click link "Deelnemer toevoegen"
        $crawler = $client->click($crawler->selectLink('Deelnemer toevoegen')->link());
        $this->assertResponseIsSuccessful();

        // submit filter form
        $crawler = $client->submitForm('Filteren', [], 'GET');
        $this->assertResponseIsSuccessful();

        // click last row (deelnemer does not exist yet)
        $crawler = $client->request('GET', $crawler->filter('.table > tr')->last()->attr('data-href'));
        $this->assertResponseIsSuccessful();

        $crawler = $client->submitForm('Opslaan', []);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testAddDeelnemerExisting()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()
            ->get(MedewerkerDao::class)
            ->findByUsername('dagbesteding_user');
        $client->loginUser($medewerker);

        // request deelnemers list
        $crawler = $client->request('GET', '/dagbesteding/deelnemers/');
        $this->assertResponseIsSuccessful();

        // click link "Deelnemer toevoegen"
        $crawler = $client->click($crawler->selectLink('Deelnemer toevoegen')->link());
        $this->assertResponseIsSuccessful();

        // submit filter form
        $crawler = $client->submitForm('Filteren', [], 'GET');
        $this->assertResponseIsSuccessful();

        // click first row (deelnemer already exists)
        $client->request('GET', $crawler->filter('.table > tr')->first()->attr('data-href'));
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testCloseAndReopenDeelnemer()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()
            ->get(MedewerkerDao::class)
            ->findByUsername('dagbesteding_user');
        $client->loginUser($medewerker);

        // request deelnemers list
        $crawler = $client->request('GET', '/dagbesteding/deelnemers/');
        $this->assertResponseIsSuccessful();

        // click first row
        $crawler = $client->request('GET', $crawler->filter('.table tbody > tr')->first()->attr('data-href'));
        $this->assertResponseIsSuccessful();

        // click link "Afsluiten"
        $crawler = $client->click($crawler->selectLink('Afsluiten')->link());
        $this->assertResponseIsSuccessful();

        // submit filter form
        $client->submitForm('Afsluiten', [
            'deelnemer_close[afsluiting]' => $this->getContainer()
                ->get(EntityManagerInterface::class)->getRepository(Afsluiting::class)
                ->findOneBy(['naam' => 'Reden 3 afsluiting deelnemer'])->getId(),
        ]);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();

        // click link "Heropenen"
        $crawler = $client->click($crawler->selectLink('Heropenen')->link());
        $this->assertResponseIsSuccessful();

        // confirm
        $client->submitForm('Heropenen');
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }
}
