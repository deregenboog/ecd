<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Taal;
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
        $eersteIntake->setIndruk('eerste');
        $laatsteIntake = new Intake();
        $laatsteIntake->setIndruk('laatste');

        // registraties are ordered desc by id
        $klant->getRegistraties()->add($laatsteRegistratie);
        $klant->getRegistraties()->add($eersteRegistratie);

        // intakes are ordered desc by id //?! wtf kan wel zijn maar er is geen ID bekend hier... dus ja. wat bedoel je hier?
        $klant->getIntakes()->add($eersteIntake);
        $klant->getIntakes()->add($laatsteIntake);

//        $intakes = $klant->getIntakes();

        $klant->updateCalculatedFields(); // this one was messy. Cleared it up. Only got called in merge.

        $this->assertSame($laatsteRegistratie, $klant->getLaatsteRegistratie());
        $this->assertSame($laatsteIntake, $klant->getLaatsteIntake());

        $d1 = $eersteIntake->getIntakedatum();
        $d2 = $klant->getEersteIntakeDatum();
        $this->assertSame($d1, $d2);
    }

    public function testSetVoorkeurstaal()
    {
        $nederlands = new Taal('Nederlands');

        $klant = new Klant();
        $klant->setVoorkeurstaal($nederlands);

        $this->assertEquals($nederlands, $klant->getVoorkeurstaal());
    }

    public function testSetVoorkeurstaalOverridingOverigeTaal()
    {
        $nederlands = new Taal('Nederlands');

        $klant = new Klant();
        $klant->addOverigeTaal($nederlands);
        $klant->setVoorkeurstaal($nederlands);

        $this->assertEquals($nederlands, $klant->getVoorkeurstaal());
        $this->assertCount(0, $klant->getOverigeTalen());
    }

    public function testReplaceVoorkeurstaal()
    {
        $nederlands = new Taal('Nederlands');
        $frans = new Taal('Frans');

        $klant = new Klant();
        $klant->setVoorkeurstaal($nederlands);
        $klant->setVoorkeurstaal($frans);

        $this->assertEquals($frans, $klant->getVoorkeurstaal());
        $this->assertCount(1, $klant->getOverigeTalen());
        $this->assertEquals($nederlands, $klant->getOverigeTalen()[0]);
    }

    public function testAddOverigeTaal()
    {
        $nederlands = new Taal('Nederlands');
        $frans = new Taal('Frans');

        $klant = new Klant();
        $klant->setVoorkeurstaal($nederlands);
        $klant->addOverigeTaal($frans);

        $this->assertEquals($nederlands, $klant->getVoorkeurstaal());
        $this->assertCount(1, $klant->getOverigeTalen());
        $this->assertEquals($frans, $klant->getOverigeTalen()[0]);
    }

    public function testAddDuplicateOverigeTaal()
    {
        $nederlands = new Taal('Nederlands');

        $klant = new Klant();
        $klant->addOverigeTaal($nederlands);
        $klant->addOverigeTaal($nederlands);

        $this->assertNull($klant->getVoorkeurstaal());
        $this->assertCount(1, $klant->getOverigeTalen());
        $this->assertEquals($nederlands, $klant->getOverigeTalen()[0]);
    }

    public function testAddOverigeTaalOverridingVoorkeurstaal()
    {
        $nederlands = new Taal('Nederlands');

        $klant = new Klant();
        $klant->setVoorkeurstaal($nederlands);
        $klant->addOverigeTaal($nederlands);

        $this->assertEquals($nederlands, $klant->getVoorkeurstaal());
        $this->assertCount(0, $klant->getOverigeTalen());
    }

    public function testRemoveOverigeTaal()
    {
        $nederlands = new Taal('Nederlands');
        $frans = new Taal('Frans');

        $klant = new Klant();
        $klant->addOverigeTaal($nederlands);
        $klant->addOverigeTaal($frans);
        $klant->removeOverigeTaal($nederlands);

        $this->assertNull($klant->getVoorkeurstaal());
        $this->assertCount(1, $klant->getOverigeTalen());
        $this->assertEquals($frans, $klant->getOverigeTalen()[0]);
    }

    public function testRemoveNonExistingOverigeTaal()
    {
        $nederlands = new Taal('Nederlands');
        $frans = new Taal('Frans');

        $klant = new Klant();
        $klant->addOverigeTaal($frans);
        $klant->removeOverigeTaal($nederlands);

        $this->assertNull($klant->getVoorkeurstaal());
        $this->assertCount(1, $klant->getOverigeTalen());
        $this->assertEquals($frans, $klant->getOverigeTalen()[0]);
    }
}
