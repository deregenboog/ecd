<?php

namespace Tests\HsBundle\Event;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManagerInterface;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Registratie;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FactuurSubscriberTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected function setUp(): void
    {
        $this->markTestSkipped();
    }

    public function testCreatingRegistratieResultsInCreatedFactuur()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $medewerker = $em->getReference(Medewerker::class, 1);
        $klus = $em->getReference(Klus::class, 1);
        $dienstverlener = $em->getReference(Dienstverlener::class, 1);

        $registratie = new Registratie($klus, $dienstverlener);
        $registratie
            ->setDatum(new \DateTime('today'))
            ->setStart(new \DateTime('10:00'))
            ->setEind(new \DateTime('12:00'))
            ->setMedewerker($medewerker)
        ;

        $em->persist($registratie);
        $em->flush();

        $this->assertEquals(5.0, $registratie->getFactuur()->getBedrag());

        return $registratie;
    }

    public function testUpdatingRegistratieResultsInUpdatedFactuur()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $registratie = $this->testCreatingRegistratieResultsInCreatedFactuur();
        $registratie->setStart(new \DateTime('09:00'));

        $em->flush();

        $this->assertEquals(7.5, $registratie->getFactuur()->getBedrag());
    }

    public function testUpdatingToAnotherMonthRegistratieResultsInCreatedFactuurAndRemovedEmptyFactuur()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $registratie = $this->testCreatingRegistratieResultsInCreatedFactuur();
        $oudeFactuur = $registratie->getFactuur();
        $this->assertEquals(5.0, $oudeFactuur->getBedrag());

        $registratie->setDatum(new \DateTime('-1 month'));

        $em->flush();

        // new Factuur created
        $this->assertNotEquals($oudeFactuur->getId(), $registratie->getFactuur()->getId());
        $this->assertEquals(5.0, $registratie->getFactuur()->getBedrag());
        // old Factuur empty, thus removed
        $this->assertEquals(0.0, $oudeFactuur->getBedrag());
        $this->assertNull($oudeFactuur->getId());
    }
}
