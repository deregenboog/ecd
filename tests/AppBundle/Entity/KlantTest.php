<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Zrm;
use PHPUnit\Framework\TestCase;
use InloopBundle\Entity\Registratie;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Intake;

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
        $laatsteIntake = new Intake();

        // registraties are ordered desc by id
        $klant->getRegistraties()->add($laatsteRegistratie);
        $klant->getRegistraties()->add($eersteRegistratie);

        // intakes are ordered desc by id
        $klant->getIntakes()->add($laatsteIntake);
        $klant->getIntakes()->add($eersteIntake);

        $klant->updateCalculatedFields();
        $this->assertSame($laatsteRegistratie, $klant->getLaatsteRegistratie());
        $this->assertSame($laatsteIntake, $klant->getLaatsteIntake());
        $this->assertSame($eersteIntake->getIntakedatum(), $klant->getEersteIntakeDatum());
    }
}
