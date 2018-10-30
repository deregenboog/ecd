<?php

namespace Tests\HsBundle\Event;

use AppBundle\Entity\Medewerker;
use AppBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManager;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Registratie;
use Symfony\Component\HttpKernel\Client;

class FactuurSubscriberTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $this->markTestSkipped();

        $fixtures = $this->loadFixtureFiles([
            '@AppBundle/DataFixtures/ORM/geslacht.yml',
            '@AppBundle/DataFixtures/ORM/klant.yml',
            '@AppBundle/DataFixtures/ORM/land.yml',
            '@AppBundle/DataFixtures/ORM/medewerker.yml',
            '@AppBundle/DataFixtures/ORM/nationaliteit.yml',
            '@AppBundle/DataFixtures/ORM/vrijwilliger.yml',
            '@AppBundle/DataFixtures/ORM/werkgebied.yml',
            '@HsBundle/DataFixtures/ORM/fixtures.yml',
        ]);

        $this->client = $this->createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testCreatingRegistratieResultsInCreatedFactuur()
    {
        $medewerker = $this->entityManager->getReference(Medewerker::class, 1);
        $klus = $this->entityManager->getReference(Klus::class, 1);
        $dienstverlener = $this->entityManager->getReference(Dienstverlener::class, 1);

        $registratie = new Registratie($klus, $dienstverlener);
        $registratie
            ->setDatum(new \DateTime('today'))
            ->setStart(new \DateTime('10:00'))
            ->setEind(new \DateTime('12:00'))
            ->setMedewerker($medewerker)
        ;

        $this->entityManager->persist($registratie);
        $this->entityManager->flush();

        $this->assertEquals(5.0, $registratie->getFactuur()->getBedrag());

        return $registratie;
    }

    public function testUpdatingRegistratieResultsInUpdatedFactuur()
    {
        $registratie = $this->testCreatingRegistratieResultsInCreatedFactuur();
        $registratie->setStart(new \DateTime('09:00'));

        $this->entityManager->flush();

        $this->assertEquals(7.5, $registratie->getFactuur()->getBedrag());
    }

    public function testUpdatingToAnotherMonthRegistratieResultsInCreatedFactuurAndRemovedEmptyFactuur()
    {
        $registratie = $this->testCreatingRegistratieResultsInCreatedFactuur();
        $oudeFactuur = $registratie->getFactuur();
        $this->assertEquals(5.0, $oudeFactuur->getBedrag());

        $registratie->setDatum(new \DateTime('-1 month'));

        $this->entityManager->flush();

        // new Factuur created
        $this->assertNotEquals($oudeFactuur->getId(), $registratie->getFactuur()->getId());
        $this->assertEquals(5.0, $registratie->getFactuur()->getBedrag());
        // old Factuur empty, thus removed
        $this->assertEquals(0.0, $oudeFactuur->getBedrag());
        $this->assertNull($oudeFactuur->getId());
    }
}
