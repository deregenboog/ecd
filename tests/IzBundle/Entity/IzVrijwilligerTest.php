<?php

namespace Tests\IzBundle\Entity;

use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Entity\Koppeling;
use PHPUnit\Framework\TestCase;

class IzVrijwilligerTest extends TestCase
{
    public function testGetHulpaanbiedingen()
    {
        $izVrijwilliger = $this->getIzVrijwilliger();

        $this->assertCount(8, $izVrijwilliger->getHulpaanbiedingen());
    }

    public function testGetGekoppeldeHulpaanbiedingen()
    {
        $izKlant = $this->getIzVrijwilliger();

        $this->assertCount(4, $izKlant->getGekoppeldeHulpaanbiedingen());
    }

    public function testGetNietGekoppeldeHulpaanbiedingen()
    {
        $izKlant = $this->getIzVrijwilliger();

        $this->assertCount(4, $izKlant->getNietGekoppeldeHulpaanbiedingen());
    }

    public function testGetKoppelingen()
    {
        $izKlant = $this->getIzVrijwilliger();

        $this->assertCount(4, $izKlant->getKoppelingen());
    }

    public function testGetOpenHulpaanbiedingen()
    {
        $izVrijwilliger = $this->getIzVrijwilliger();

        $this->assertCount(2, $izVrijwilliger->getOpenHulpaanbiedingen());
    }

    public function testGetAfgeslotenHulpaanbiedingen()
    {
        $izVrijwilliger = $this->getIzVrijwilliger();

        $this->assertCount(2, $izVrijwilliger->getAfgeslotenHulpaanbiedingen());
    }

    public function testGetActieveKoppelingen()
    {
        $izVrijwilliger = $this->getIzVrijwilliger();

        $this->assertCount(2, $izVrijwilliger->getActieveKoppelingen());
    }

    public function testGetAfgeslotenKoppelingen()
    {
        $izVrijwilliger = $this->getIzVrijwilliger();

        $this->assertCount(2, $izVrijwilliger->getAfgeslotenKoppelingen());
    }

    protected function getIzVrijwilliger()
    {
        $izVrijwilliger = new IzVrijwilliger();

        $openHulpvraag = new Hulpaanbod();
        $izVrijwilliger->addHulpaanbod($openHulpvraag);

        $openHulpvraag = new Hulpaanbod();
        $openHulpvraag->setEinddatum(new \DateTime('tomorrow'));
        $izVrijwilliger->addHulpaanbod($openHulpvraag);

        $afgeslotenHulpvraag = new Hulpaanbod();
        $afgeslotenHulpvraag->setEinddatum(new \DateTime('yesterday'));
        $izVrijwilliger->addHulpaanbod($afgeslotenHulpvraag);

        $afgeslotenHulpvraag = new Hulpaanbod();
        $afgeslotenHulpvraag->setEinddatum(new \DateTime('today'));
        $izVrijwilliger->addHulpaanbod($afgeslotenHulpvraag);

        $hulpaanbod = new Hulpaanbod();
        $actieveKoppeling = new Koppeling(new Hulpvraag(), $hulpaanbod);
        $hulpaanbod->setKoppeling($actieveKoppeling);
        $izVrijwilliger->addHulpaanbod($hulpaanbod);

        $hulpaanbod = new Hulpaanbod();
        $actieveKoppeling = new Koppeling(new Hulpvraag(), $hulpaanbod);
        $actieveKoppeling->setAfsluitdatum(new \DateTime('tomorrow'));
        $hulpaanbod->setKoppeling($actieveKoppeling);
        $izVrijwilliger->addHulpaanbod($hulpaanbod);

        $hulpaanbod = new Hulpaanbod();
        $afgeslotenKoppeling = new Koppeling(new Hulpvraag(), $hulpaanbod);
        $afgeslotenKoppeling->setAfsluitdatum(new \DateTime('yesterday'));
        $hulpaanbod->setKoppeling($afgeslotenKoppeling);
        $izVrijwilliger->addHulpaanbod($hulpaanbod);

        $hulpaanbod = new Hulpaanbod();
        $afgeslotenKoppeling = new Koppeling(new Hulpvraag(), $hulpaanbod);
        $afgeslotenKoppeling->setAfsluitdatum(new \DateTime('today'));
        $hulpaanbod->setKoppeling($afgeslotenKoppeling);
        $izVrijwilliger->addHulpaanbod($hulpaanbod);

        return $izVrijwilliger;
    }
}
