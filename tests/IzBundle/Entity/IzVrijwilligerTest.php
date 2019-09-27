<?php

namespace Tests\IzBundle\Entity;

use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\IzVrijwilliger;
use PHPUnit\Framework\TestCase;

class IzVrijwilligerTest extends TestCase
{
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

        $actieveKoppeling = new Hulpaanbod();
        $actieveKoppeling->setHulpvraag(new Hulpvraag());
        $izVrijwilliger->addHulpaanbod($actieveKoppeling);

        $actieveKoppeling = new Hulpaanbod();
        $actieveKoppeling->setHulpvraag(new Hulpvraag())->setKoppelingEinddatum(new \DateTime('tomorrow'));
        $izVrijwilliger->addHulpaanbod($actieveKoppeling);

        $afgeslotenKoppeling = new Hulpaanbod();
        $afgeslotenKoppeling->setHulpvraag(new Hulpvraag())->setKoppelingEinddatum(new \DateTime('yesterday'));
        $izVrijwilliger->addHulpaanbod($afgeslotenKoppeling);

        $afgeslotenKoppeling = new Hulpaanbod();
        $afgeslotenKoppeling->setHulpvraag(new Hulpvraag())->setKoppelingEinddatum(new \DateTime('today'));
        $izVrijwilliger->addHulpaanbod($afgeslotenKoppeling);

        return $izVrijwilliger;
    }
}
