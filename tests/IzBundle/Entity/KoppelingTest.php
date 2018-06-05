<?php

namespace Tests\IzBundle\Entity;

use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\AfsluitredenKoppeling;

class KoppelingTest extends \PHPUnit_Framework_TestCase
{
    public function testKoppelingStartdatumIsSetOnBothSides()
    {
        $startdatum = new \DateTime('2017-05-05');

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setKoppelingStartdatum($startdatum);
        $this->assertTrue($startdatum === $hulpvraag->getKoppelingStartdatum());
        $this->assertTrue($startdatum === $hulpaanbod->getKoppelingStartdatum());
        $this->assertTrue($hulpvraag->getTussenevaluatiedatum() === $hulpaanbod->getTussenevaluatiedatum());
        $this->assertTrue($hulpvraag->getEindevaluatiedatum() === $hulpaanbod->getEindevaluatiedatum());

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpaanbod->setHulpvraag($hulpvraag);

        $hulpaanbod->setKoppelingStartdatum($startdatum);
        $this->assertTrue($startdatum === $hulpvraag->getKoppelingStartdatum());
        $this->assertTrue($startdatum === $hulpaanbod->getKoppelingStartdatum());
        $this->assertTrue($hulpvraag->getTussenevaluatiedatum() === $hulpaanbod->getTussenevaluatiedatum());
        $this->assertTrue($hulpvraag->getEindevaluatiedatum() === $hulpaanbod->getEindevaluatiedatum());
    }

    public function testTussenevaluatiedatumIsSetOnBothSides()
    {
        $datum = new \DateTime('2017-05-05');

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setTussenevaluatiedatum($datum);
        $this->assertTrue($datum === $hulpvraag->getTussenevaluatiedatum());
        $this->assertTrue($datum === $hulpaanbod->getTussenevaluatiedatum());

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpaanbod->setHulpvraag($hulpvraag);

        $hulpaanbod->setTussenevaluatiedatum($datum);
        $this->assertTrue($datum === $hulpvraag->getTussenevaluatiedatum());
        $this->assertTrue($datum === $hulpaanbod->getTussenevaluatiedatum());
    }

    public function testEindevaluatiedatumIsSetOnBothSides()
    {
        $datum = new \DateTime('2017-05-05');

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setEindevaluatiedatum($datum);
        $this->assertTrue($datum === $hulpvraag->getEindevaluatiedatum());
        $this->assertTrue($datum === $hulpaanbod->getEindevaluatiedatum());

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpaanbod->setHulpvraag($hulpvraag);

        $hulpaanbod->setEindevaluatiedatum($datum);
        $this->assertTrue($datum === $hulpvraag->getEindevaluatiedatum());
        $this->assertTrue($datum === $hulpaanbod->getEindevaluatiedatum());
    }

    public function testKoppelingEinddatumIsSetOnBothSides()
    {
        $einddatum = new \DateTime('2017-05-05');

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setKoppelingEinddatum($einddatum);
        $this->assertTrue($einddatum === $hulpvraag->getKoppelingEinddatum());
        $this->assertTrue($einddatum === $hulpaanbod->getKoppelingEinddatum());

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpaanbod->setHulpvraag($hulpvraag);

        $hulpaanbod->setKoppelingEinddatum($einddatum);
        $this->assertTrue($einddatum === $hulpvraag->getKoppelingEinddatum());
        $this->assertTrue($einddatum === $hulpaanbod->getKoppelingEinddatum());
    }

    public function testKoppelingSuccesvolIsSetOnBothSides()
    {
        $koppelingSuccesvol = true;

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setKoppelingSuccesvol($koppelingSuccesvol);
        $this->assertTrue($koppelingSuccesvol === $hulpvraag->isKoppelingSuccesvol());
        $this->assertTrue($koppelingSuccesvol === $hulpaanbod->isKoppelingSuccesvol());

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpaanbod->setHulpvraag($hulpvraag);

        $hulpaanbod->setKoppelingSuccesvol($koppelingSuccesvol);
        $this->assertTrue($koppelingSuccesvol === $hulpvraag->isKoppelingSuccesvol());
        $this->assertTrue($koppelingSuccesvol === $hulpaanbod->isKoppelingSuccesvol());
    }

    public function testAfsluitredenKoppelingIsSetOnBothSides()
    {
        $afsluitreden = new AfsluitredenKoppeling();

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setAfsluitredenKoppeling($afsluitreden);
        $this->assertTrue($afsluitreden === $hulpvraag->getAfsluitredenKoppeling());
        $this->assertTrue($afsluitreden === $hulpaanbod->getAfsluitredenKoppeling());

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpaanbod->setHulpvraag($hulpvraag);

        $hulpaanbod->setAfsluitredenKoppeling($afsluitreden);
        $this->assertTrue($afsluitreden === $hulpvraag->getAfsluitredenKoppeling());
        $this->assertTrue($afsluitreden === $hulpaanbod->getAfsluitredenKoppeling());
    }
}
