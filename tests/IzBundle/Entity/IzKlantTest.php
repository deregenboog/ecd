<?php

namespace Tests\IzBundle\Entity;

use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\IzKlant;
use PHPUnit\Framework\TestCase;

class IzKlantTest extends TestCase
{
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

        $actieveKoppeling = new Hulpvraag();
        $actieveKoppeling->setHulpaanbod(new Hulpaanbod());
        $izKlant->addHulpvraag($actieveKoppeling);

        $actieveKoppeling = new Hulpvraag();
        $actieveKoppeling->setHulpaanbod(new Hulpaanbod())->setKoppelingEinddatum(new \DateTime('tomorrow'));
        $izKlant->addHulpvraag($actieveKoppeling);

        $afgeslotenKoppeling = new Hulpvraag();
        $afgeslotenKoppeling->setHulpaanbod(new Hulpaanbod())->setKoppelingEinddatum(new \DateTime('yesterday'));
        $izKlant->addHulpvraag($afgeslotenKoppeling);

        $afgeslotenKoppeling = new Hulpvraag();
        $afgeslotenKoppeling->setHulpaanbod(new Hulpaanbod())->setKoppelingEinddatum(new \DateTime('today'));
        $izKlant->addHulpvraag($afgeslotenKoppeling);

        return $izKlant;
    }
}
