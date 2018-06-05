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
        $tussenevaluatiedatum = (clone $startdatum)->modify('+3 months');
        $eindevaluatiedatum = (clone $startdatum)->modify('+6 months');

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setKoppelingStartdatum($startdatum);
        $this->assertEquals($startdatum, $hulpvraag->getKoppelingStartdatum());
        $this->assertEquals($startdatum, $hulpaanbod->getKoppelingStartdatum());
        $this->assertEquals($tussenevaluatiedatum, $hulpvraag->getTussenevaluatiedatum());
        $this->assertEquals($hulpvraag->getTussenevaluatiedatum(), $hulpaanbod->getTussenevaluatiedatum());
        $this->assertEquals($eindevaluatiedatum, $hulpvraag->getEindevaluatiedatum());
        $this->assertEquals($hulpvraag->getEindevaluatiedatum(), $hulpaanbod->getEindevaluatiedatum());

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpaanbod->setHulpvraag($hulpvraag);

        $hulpaanbod->setKoppelingStartdatum($startdatum);
        $this->assertEquals($startdatum, $hulpvraag->getKoppelingStartdatum());
        $this->assertEquals($startdatum, $hulpaanbod->getKoppelingStartdatum());
        $this->assertEquals($tussenevaluatiedatum, $hulpaanbod->getTussenevaluatiedatum());
        $this->assertEquals($hulpvraag->getTussenevaluatiedatum(), $hulpaanbod->getTussenevaluatiedatum());
        $this->assertEquals($eindevaluatiedatum, $hulpaanbod->getEindevaluatiedatum());
        $this->assertEquals($hulpvraag->getEindevaluatiedatum(), $hulpaanbod->getEindevaluatiedatum());
    }

    public function testKoppelingUpdatingStartdatumUpdatesEvaluatiedata()
    {
        $startdatum = new \DateTime('2017-05-05');
        $tussenevaluatiedatum = (clone $startdatum)->modify('+3 months');
        $eindevaluatiedatum = (clone $startdatum)->modify('+6 months');

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setKoppelingStartdatum($startdatum);
        $this->assertEquals($startdatum, $hulpvraag->getKoppelingStartdatum());
        $this->assertEquals($tussenevaluatiedatum, $hulpvraag->getTussenevaluatiedatum());
        $this->assertEquals($eindevaluatiedatum, $hulpvraag->getEindevaluatiedatum());

        $hulpvraag->setKoppelingStartdatum($startdatum->modify('+1 week'));
        $this->assertEquals($startdatum, $hulpvraag->getKoppelingStartdatum());
        $this->assertEquals((clone $startdatum)->modify('+3 months'), $hulpvraag->getTussenevaluatiedatum());
        $this->assertEquals((clone $startdatum)->modify('+6 months'), $hulpvraag->getEindevaluatiedatum());
    }

    public function testTussenevaluatiedatumIsSetOnBothSides()
    {
        $datum = new \DateTime('2017-05-05');

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setTussenevaluatiedatum($datum);
        $this->assertEquals($datum, $hulpvraag->getTussenevaluatiedatum());
        $this->assertEquals($datum, $hulpaanbod->getTussenevaluatiedatum());

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpaanbod->setHulpvraag($hulpvraag);

        $hulpaanbod->setTussenevaluatiedatum($datum);
        $this->assertEquals($datum, $hulpvraag->getTussenevaluatiedatum());
        $this->assertEquals($datum, $hulpaanbod->getTussenevaluatiedatum());
    }

    public function testEindevaluatiedatumIsSetOnBothSides()
    {
        $datum = new \DateTime('2017-05-05');

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setEindevaluatiedatum($datum);
        $this->assertEquals($datum, $hulpvraag->getEindevaluatiedatum());
        $this->assertEquals($datum, $hulpaanbod->getEindevaluatiedatum());

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpaanbod->setHulpvraag($hulpvraag);

        $hulpaanbod->setEindevaluatiedatum($datum);
        $this->assertEquals($datum, $hulpvraag->getEindevaluatiedatum());
        $this->assertEquals($datum, $hulpaanbod->getEindevaluatiedatum());
    }

    public function testKoppelingEinddatumIsSetOnBothSides()
    {
        $einddatum = new \DateTime('2017-05-05');

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setKoppelingEinddatum($einddatum);
        $this->assertEquals($einddatum, $hulpvraag->getKoppelingEinddatum());
        $this->assertEquals($einddatum, $hulpaanbod->getKoppelingEinddatum());

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpaanbod->setHulpvraag($hulpvraag);

        $hulpaanbod->setKoppelingEinddatum($einddatum);
        $this->assertEquals($einddatum, $hulpvraag->getKoppelingEinddatum());
        $this->assertEquals($einddatum, $hulpaanbod->getKoppelingEinddatum());
    }

    public function testKoppelingSuccesvolIsSetOnBothSides()
    {
        $koppelingSuccesvol = true;

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpvraag->setHulpaanbod($hulpaanbod);

        $hulpvraag->setKoppelingSuccesvol($koppelingSuccesvol);
        $this->assertEquals($koppelingSuccesvol, $hulpvraag->isKoppelingSuccesvol());
        $this->assertEquals($koppelingSuccesvol, $hulpaanbod->isKoppelingSuccesvol());

        $hulpvraag = new Hulpvraag();
        $hulpaanbod = new Hulpaanbod();
        $hulpaanbod->setHulpvraag($hulpvraag);

        $hulpaanbod->setKoppelingSuccesvol($koppelingSuccesvol);
        $this->assertEquals($koppelingSuccesvol, $hulpvraag->isKoppelingSuccesvol());
        $this->assertEquals($koppelingSuccesvol, $hulpaanbod->isKoppelingSuccesvol());

        $koppelingSuccesvol = false;

        $hulpvraag->setKoppelingSuccesvol($koppelingSuccesvol);
        $this->assertEquals($koppelingSuccesvol, $hulpvraag->isKoppelingSuccesvol());
        $this->assertEquals($koppelingSuccesvol, $hulpaanbod->isKoppelingSuccesvol());

        $hulpaanbod->setKoppelingSuccesvol($koppelingSuccesvol);
        $this->assertEquals($koppelingSuccesvol, $hulpvraag->isKoppelingSuccesvol());
        $this->assertEquals($koppelingSuccesvol, $hulpaanbod->isKoppelingSuccesvol());
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
