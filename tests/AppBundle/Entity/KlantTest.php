<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Zrm;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Registratie;
use PHPUnit\Framework\TestCase;

class KlantTest extends TestCase
{
    public function testAddZrm()
    {
        $klant = new Klant();
        $zrm = Zrm::create();

        $klant->addZrm($zrm);

        $this->assertSame($klant, $zrm->getKlant());
        $this->assertContains($zrm, $klant->getZrms());
    }

    public function testUpdateCalculatedFields()
    {
        $klant = new Klant();

        $this->assertNull($klant->getLaatsteRegistratie());
        $this->assertNull($klant->getLaatsteIntake());
        $this->assertNull($klant->getEersteIntakeDatum());

        $eersteRegistratie = new Registratie($klant, new Locatie());
        $laatsteRegistratie = new Registratie($klant, new Locatie());
        $eersteIntake = new Intake();
        $eersteIntake->setIndruk("eerste");
        $laatsteIntake = new Intake();
        $laatsteIntake->setIndruk("laatste");

        // registraties are ordered desc by id
        $klant->getRegistraties()->add($laatsteRegistratie);
        $klant->getRegistraties()->add($eersteRegistratie);

        // intakes are ordered desc by id //?! wtf kan wel zijn maar er is geen ID bekend hier... dus ja. wat bedoel je hier?
        $klant->getIntakes()->add($eersteIntake);
        $klant->getIntakes()->add($laatsteIntake);

//        $intakes = $klant->getIntakes();

        $klant->updateCalculatedFields(); //this one was messy. Cleared it up. Only got called in merge.

        $this->assertSame($laatsteRegistratie, $klant->getLaatsteRegistratie());
        $this->assertSame($laatsteIntake, $klant->getLaatsteIntake());

        $d1 = $eersteIntake->getIntakedatum();
        $d2 = $klant->getEersteIntakeDatum();
        $this->assertSame($d1, $d2);
    }
}
