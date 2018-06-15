<?php

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Klus;

class KlantTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider nameProvider
     *
     * @param string $voornaam
     * @param string $tussenvoegsel
     * @param string $achternaam
     * @param string $exptected
     */
    public function testToStringResultsInFomattedName($voornaam, $tussenvoegsel, $achternaam, $exptected)
    {
        $klant = new Klant();
        $klant->setVoornaam($voornaam)->setTussenvoegsel($tussenvoegsel)->setAchternaam($achternaam);
        $this->assertEquals($exptected, (string) $klant);
    }

    public function nameProvider()
    {
        return [
            [null, null, null, ''],
            [null, null, 'Brink', 'Brink'],
            [null, 'van der', null, 'van der'],
            [null, 'van der', 'Brink', 'Brink, van der'],
            ['Piet', null, null, 'Piet'],
            ['Piet', null, 'Brink', 'Brink, Piet'],
            ['Piet', 'van der', null, 'Piet van der'],
            ['Piet', 'van der', 'Brink', 'Brink, Piet van der'],
        ];
    }

    public function testModifyingKlusResultsInStatusUpdated()
    {
        $klant = new Klant();
        $this->assertFalse($klant->isActief());

        $klus = new Klus();
        $klant->addKlus($klus);
        $this->assertTrue($klant->isActief());

        $klus->setEinddatum(new \DateTime('today'));
        $this->assertFalse($klant->isActief());

//         $dienstverlener = new Dienstverlener();
//         $klus->addDienstverlener($dienstverlener);
//         $this->assertEquals(Klus::STATUS_IN_BEHANDELING, $klus->getStatus());

//         $klus->setOnHold(true);
//         $this->assertEquals(Klus::STATUS_ON_HOLD, $klus->getStatus());

//         $klus->removeDienstverlener($dienstverlener);
//         $this->assertEquals(Klus::STATUS_ON_HOLD, $klus->getStatus());

//         $klus->setOnHold(false);
//         $this->assertEquals(Klus::STATUS_OPENSTAAND, $klus->getStatus());

//         $klus->addDienstverlener($dienstverlener);
//         $this->assertEquals(Klus::STATUS_IN_BEHANDELING, $klus->getStatus());

//         $klus->setEinddatum(new \DateTime('tomorrow'));
//         $this->assertEquals(Klus::STATUS_IN_BEHANDELING, $klus->getStatus());

//         $klus->setEinddatum(new \DateTime('today'));
//         $this->assertEquals(Klus::STATUS_AFGEROND, $klus->getStatus());

//         $klus->setOnHold(true);
//         $this->assertEquals(Klus::STATUS_AFGEROND, $klus->getStatus());
    }
}
