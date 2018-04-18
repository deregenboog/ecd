<?php

namespace Tests\IzBundle\Entity;

use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\Koppeling;
use PHPUnit\Framework\TestCase;

class IzKlantTest extends TestCase
{
    public function testGetHulpvragen()
    {
        $izKlant = $this->getIzKlant();

        $this->assertCount(8, $izKlant->getHulpvragen());
    }

    public function testGetGekoppeldeHulpvragen()
    {
        $izKlant = $this->getIzKlant();

        $this->assertCount(4, $izKlant->getGekoppeldeHulpvragen());
    }

    public function testGetNietGekoppeldeHulpvragen()
    {
        $izKlant = $this->getIzKlant();

        $this->assertCount(4, $izKlant->getNietGekoppeldeHulpvragen());
    }

    public function testGetKoppelingen()
    {
        $izKlant = $this->getIzKlant();

        $this->assertCount(4, $izKlant->getKoppelingen());
    }

    public function testGetOpenHulpvragen()
    {
        $izKlant = $this->getIzKlant();

        $this->assertCount(2, $izKlant->getOpenHulpvragen());
    }

    public function testGetAfgeslotenHulpvragen()
    {
        $izKlant = $this->getIzKlant();

        $this->assertCount(2, $izKlant->getAfgeslotenHulpvragen());
    }

    public function testGetActieveKoppelingen()
    {
        $izKlant = $this->getIzKlant();

        $this->assertCount(2, $izKlant->getActieveKoppelingen());
    }

    public function testGetAfgeslotenKoppelingen()
    {
        $izKlant = $this->getIzKlant();

        $this->assertCount(2, $izKlant->getAfgeslotenKoppelingen());
    }

    protected function getIzKlant()
    {
        $izKlant = new IzKlant();

        $openHulpvraag = new Hulpvraag();
        $izKlant->addHulpvraag($openHulpvraag);

        $openHulpvraag = new Hulpvraag();
        $openHulpvraag->setEinddatum(new \DateTime('tomorrow'));
        $izKlant->addHulpvraag($openHulpvraag);

        $afgeslotenHulpvraag = new Hulpvraag();
        $afgeslotenHulpvraag->setEinddatum(new \DateTime('yesterday'));
        $izKlant->addHulpvraag($afgeslotenHulpvraag);

        $afgeslotenHulpvraag = new Hulpvraag();
        $afgeslotenHulpvraag->setEinddatum(new \DateTime('today'));
        $izKlant->addHulpvraag($afgeslotenHulpvraag);

        $hulpvraag = new Hulpvraag();
        $actieveKoppeling = new Koppeling($hulpvraag, new Hulpaanbod());
        $hulpvraag->setKoppeling($actieveKoppeling);
        $izKlant->addHulpvraag($hulpvraag);

        $hulpvraag = new Hulpvraag();
        $actieveKoppeling = new Koppeling($hulpvraag, new Hulpaanbod());
        $actieveKoppeling->setAfsluitdatum(new \DateTime('tomorrow'));
        $hulpvraag->setKoppeling($actieveKoppeling);
        $izKlant->addHulpvraag($hulpvraag);

        $hulpvraag = new Hulpvraag();
        $afgeslotenKoppeling = new Koppeling($hulpvraag, new Hulpaanbod());
        $afgeslotenKoppeling->setAfsluitdatum(new \DateTime('yesterday'));
        $hulpvraag->setKoppeling($afgeslotenKoppeling);
        $izKlant->addHulpvraag($hulpvraag);

        $hulpvraag = new Hulpvraag();
        $afgeslotenKoppeling = new Koppeling($hulpvraag, new Hulpaanbod());
        $afgeslotenKoppeling->setAfsluitdatum(new \DateTime('today'));
        $hulpvraag->setKoppeling($afgeslotenKoppeling);
        $izKlant->addHulpvraag($hulpvraag);

        return $izKlant;
    }
}
