<?php

declare(strict_types=1);

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Registratie;
use HsBundle\Entity\Vrijwilliger;
use PHPUnit\Framework\TestCase;

class KlusTest extends TestCase
{
    public function testAddingRegistratieResultsInAddingArbeider()
    {
        $klus = new Klus();
        $dienstverlener = new Dienstverlener();
        $this->assertFalse($klus->getDienstverleners()->contains($dienstverlener));

        $registratie = new Registratie();
        $registratie->setArbeider($dienstverlener);
        $klus->addRegistratie($registratie);
        $this->assertTrue($klus->getDienstverleners()->contains($dienstverlener));

        $vrijwilliger = new Vrijwilliger();
        $this->assertFalse($klus->getVrijwilligers()->contains($vrijwilliger));

        $registratie = new Registratie();
        $registratie->setArbeider($vrijwilliger);
        $klus->addRegistratie($registratie);
        $this->assertTrue($klus->getVrijwilligers()->contains($vrijwilliger));
    }

    public function testModifyingResultsInStatusUpdated()
    {
        $klus = new Klus();
        $this->assertEquals(Klus::STATUS_OPENSTAAND, $klus->getStatus());

        $dienstverlener = new Dienstverlener();
        $klus->addDienstverlener($dienstverlener);
        $this->assertEquals(Klus::STATUS_IN_BEHANDELING, $klus->getStatus());

        $klus->setOnHold(true);
        $this->assertEquals(Klus::STATUS_ON_HOLD, $klus->getStatus());

        $klus->removeDienstverlener($dienstverlener);
        $this->assertEquals(Klus::STATUS_ON_HOLD, $klus->getStatus());

        $klus->setOnHold(false);
        $this->assertEquals(Klus::STATUS_OPENSTAAND, $klus->getStatus());

        $klus->addDienstverlener($dienstverlener);
        $this->assertEquals(Klus::STATUS_IN_BEHANDELING, $klus->getStatus());

        $klus->setEinddatum(new \DateTime('tomorrow'));
        $this->assertEquals(Klus::STATUS_IN_BEHANDELING, $klus->getStatus());

        $klus->setEinddatum(new \DateTime('today'));
        $this->assertEquals(Klus::STATUS_AFGEROND, $klus->getStatus());

        $klus->setOnHold(true);
        $this->assertEquals(Klus::STATUS_AFGEROND, $klus->getStatus());
    }
}
