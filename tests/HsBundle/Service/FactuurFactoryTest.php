<?php

namespace Tests\HsBundle\Event;

use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Registratie;
use HsBundle\Entity\Vrijwilliger;
use HsBundle\Service\FactuurFactory;
use PHPUnit\Framework\TestCase;

class FactuurFactoryTest extends TestCase
{
    public function dataProvider()
    {
        $klant = new Klant();

        $klus = new Klus();
        $klant->addKlus($klus);

        $dienstverlener = $this->createMock(Dienstverlener::class);
        $klus->addDienstverlener($dienstverlener);

        $vrijwilliger = $this->createMock(Vrijwilliger::class);
        $klus->addVrijwilliger($vrijwilliger);

        $registratie = new Registratie();
        $registratie->setStart(new \DateTime('yesterday 10:00'))->setEind(new \DateTime('yesterday 13:00'));
        $registratie->setKlus($klus)->setArbeider($dienstverlener);

        $registratieVrijwilliger = new Registratie();
        $registratieVrijwilliger->setKlus($klus)->setArbeider($vrijwilliger);

        $declaratie = new Declaratie();
        $declaratie->setKlus($klus);

        return [
            [$klant, 0.0],
            [$klant, 0.0],
            [$klant, 0.0],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCreateFactuurForKlant(Klant $klant, $expectedBedrag)
    {
        $this->markTestSkipped();

        $factory = new FactuurFactory();
        $factuur = $factory->create($klant);

        $this->assertRegExp('#^\d+/\d{6}$#', $factuur->getNummer());
        $this->assertRegExp('#^Factuurnr: \d+/\d{6} van \d{2}-\d{2}-\d{4} t/m \d{2}-\d{2}-\d{4}$#', $factuur->getBetreft());
        $this->assertEquals(new \DateTime('today'), $factuur->getDatum());
        $this->assertEquals($expectedBedrag, $factuur->getBedrag());
    }
}
