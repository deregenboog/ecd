<?php

namespace Tests\HsBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Entity\Medewerker;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Document;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Memo;
use HsBundle\Entity\Registratie;

class DienstverlenerTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $appKlant = $this->createMock(AppKlant::class);
        $appKlant->method('__toString')->willReturn('Piet Jansen');

        $dienstverlener = new Dienstverlener($appKlant);
        $this->assertEquals('Piet Jansen', (string) $dienstverlener);
    }

    public function testIsDeletable()
    {
        $dienstverlener = new Dienstverlener();
        $this->assertTrue($dienstverlener->isDeletable());

        $dienstverlener->addDocument(new Document());
        $this->assertFalse($dienstverlener->isDeletable());

        $dienstverlener = new Dienstverlener();
        $dienstverlener->addMemo(new Memo());
        $this->assertFalse($dienstverlener->isDeletable());

        $dienstverlener = new Dienstverlener();
        $klus = new Klus(new Klant(), new Medewerker());
        $dienstverlener->addKlus($klus);
        $this->assertFalse($dienstverlener->isDeletable());

        $dienstverlener = new Dienstverlener();
        $dienstverlener->addRegistratie(new Registratie($klus));
        $this->assertFalse($dienstverlener->isDeletable());
    }
}
