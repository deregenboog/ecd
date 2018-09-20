<?php

namespace Tests\HsBundle\Entity;

use AppBundle\Entity\Medewerker;
use HsBundle\Entity\Document;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Memo;
use HsBundle\Entity\Registratie;
use HsBundle\Entity\Vrijwilliger;

class VrijwilligerTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $appVrijwilliger = new \AppBundle\Entity\Vrijwilliger();
        $appVrijwilliger->setVoornaam('Piet')->setAchternaam('Jansen');

        $vrijwilliger = new Vrijwilliger($appVrijwilliger);
        $this->assertEquals('Jansen, Piet', (string) $vrijwilliger);
    }

    public function testIsDeletable()
    {
        $vrijwilliger = new Vrijwilliger();
        $this->assertTrue($vrijwilliger->isDeletable());

        $vrijwilliger->addDocument(new Document());
        $this->assertFalse($vrijwilliger->isDeletable());

        $vrijwilliger = new Vrijwilliger();
        $vrijwilliger->addMemo(new Memo());
        $this->assertFalse($vrijwilliger->isDeletable());

        $vrijwilliger = new Vrijwilliger();
        $klus = new Klus(new Klant(), new Medewerker());
        $vrijwilliger->addKlus($klus);
        $this->assertFalse($vrijwilliger->isDeletable());

        $vrijwilliger = new Vrijwilliger();
        $vrijwilliger->addRegistratie(new Registratie($klus));
        $this->assertFalse($vrijwilliger->isDeletable());
    }
}
