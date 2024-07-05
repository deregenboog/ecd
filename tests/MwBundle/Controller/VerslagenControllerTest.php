<?php

declare(strict_types=1);

namespace Tests\MwBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Doctrine\ORM\EntityManagerInterface;
use InloopBundle\Entity\Locatie;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use MwBundle\Entity\Verslag;

class VerslagenControllerTest extends WebTestCase
{
    public function testAddDocument()
    {
        $client = static::createClient();

        $user = static::getContainer()
            ->get(MedewerkerDao::class)
            ->findByUsername('admin');
        $client->loginUser($user);

        $crawler = $client->request('GET', '/mw/klanten/');
        $this->assertStatusCode(200, $client);

        $rows = $crawler->filter('.table tbody tr');
        $crawler = $client->request('GET', $rows->first()->attr('data-href'));
        $this->assertStatusCode(200, $client);

        $this->assertCount(0, $crawler->filter('h4')); // geen verslagen

        $link = $crawler->selectLink('Verslag toevoegen')->link();
        $crawler = $client->click($link);
        $this->assertStatusCode(200, $client);

        $client->submitForm('Opslaan', [
            'verslag' => [
                'locatie' => static::getContainer()->get(EntityManagerInterface::class)
                    ->getRepository(Locatie::class)->findOneByNaam('AMOC Stadhouderskade')->getId(),
                'access' => Verslag::ACCESS_ALL,
                'opmerking' => 'Dit is mijn opmerking',
            ],
        ]);
        $crawler = $client->followRedirect();
        $this->assertCount(1, $crawler->filter('h4')); // 1 verslag
    }
}
