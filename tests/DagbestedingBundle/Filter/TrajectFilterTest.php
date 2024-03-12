<?php

namespace Tests\DagbestedingBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use DagbestedingBundle\Entity\Deelnemer;
use DagbestedingBundle\Entity\Locatie;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Entity\Trajectcoach;
use DagbestedingBundle\Entity\Trajectsoort;
use DagbestedingBundle\Filter\ResultaatgebiedFilter;
use DagbestedingBundle\Filter\TrajectFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class TrajectFilterTest extends TestCase
{
    public function testApplyTo()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $filter = new TrajectFilter();

        $builder = new QueryBuilder($em);
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(1, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('today'));

        $builder = new QueryBuilder($em);
        $filter->id = 123;
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE traject.id = :id AND (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today)';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(2, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('id'));

        $builder = new QueryBuilder($em);
        $filter->soort = new Trajectsoort();
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE traject.id = :id AND traject.soort = :soort AND (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today)';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(3, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('soort'));

        $builder = new QueryBuilder($em);
        $filter->medewerker = new Medewerker();
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE traject.id = :id AND traject.soort = :soort AND trajectcoach.medewerker = :medewerker AND (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today)';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(4, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('medewerker'));

        $builder = new QueryBuilder($em);
        $filter->trajectcoach = new Trajectcoach();
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE traject.id = :id AND traject.soort = :soort AND trajectcoach.medewerker = :medewerker AND traject.trajectcoach = :trajectcoach AND (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today)';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(5, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('trajectcoach'));

        $builder = (new QueryBuilder($em))->from(Traject::class, 'traject');
        $filter->project = new Project();
        $filter->applyTo($builder);
        $expected = 'SELECT FROM DagbestedingBundle\Entity\Traject traject INNER JOIN deelnames.project project WITH project = :project WHERE traject.id = :id AND traject.soort = :soort AND trajectcoach.medewerker = :medewerker AND traject.trajectcoach = :trajectcoach AND (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today)';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(6, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('project'));

        $builder = (new QueryBuilder($em))->from(Traject::class, 'traject');
        $filter->locatie = new Locatie();
        $filter->applyTo($builder);
        $expected = 'SELECT FROM DagbestedingBundle\Entity\Traject traject INNER JOIN deelnames.project project WITH project = :project INNER JOIN traject.locaties locatie WITH locatie = :locatie WHERE traject.id = :id AND traject.soort = :soort AND trajectcoach.medewerker = :medewerker AND traject.trajectcoach = :trajectcoach AND (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today)';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(7, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('locatie'));

        $builder = new QueryBuilder($em);
        $filter = new TrajectFilter();
        $filter->startdatum = new AppDateRangeModel(new \DateTime('2024-01-01'), new \DateTime('2024-02-27'));
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE traject.startdatum >= :startdatum_van AND traject.startdatum <= :startdatum_tot AND (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today)';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(3, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('startdatum_van'));
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('startdatum_tot'));

        $builder = new QueryBuilder($em);
        $filter->evaluatiedatum = new AppDateRangeModel(new \DateTime('2024-01-01'), new \DateTime('2024-02-27'));
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE traject.startdatum >= :startdatum_van AND traject.startdatum <= :startdatum_tot AND traject.evaluatiedatum >= :evaluatiedatum_van AND traject.evaluatiedatum <= :evaluatiedatum_tot AND (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today)';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(5, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('evaluatiedatum_van'));
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('evaluatiedatum_tot'));

        $builder = new QueryBuilder($em);
        $filter->einddatum = new AppDateRangeModel(new \DateTime('2024-01-01'), new \DateTime('2024-02-27'));
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE traject.startdatum >= :startdatum_van AND traject.startdatum <= :startdatum_tot AND traject.evaluatiedatum >= :evaluatiedatum_van AND traject.evaluatiedatum <= :evaluatiedatum_tot AND traject.einddatum >= :einddatum_van AND traject.einddatum <= :einddatum_tot AND (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today)';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(7, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('einddatum_van'));
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('einddatum_tot'));

        $builder = new QueryBuilder($em);
        $filter->afsluitdatum = new AppDateRangeModel(new \DateTime('2024-01-01'), new \DateTime('2024-02-27'));
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE traject.startdatum >= :startdatum_van AND traject.startdatum <= :startdatum_tot AND traject.evaluatiedatum >= :evaluatiedatum_van AND traject.evaluatiedatum <= :evaluatiedatum_tot AND traject.einddatum >= :einddatum_van AND traject.einddatum <= :einddatum_tot AND traject.afsluitdatum >= :afsluitdatum_van AND traject.afsluitdatum <= :afsluitdatum_tot AND (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today)';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(9, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('afsluitdatum_van'));
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('afsluitdatum_tot'));

        $builder = (new QueryBuilder($em))->from(Traject::class, 'traject');
        $filter = new TrajectFilter();
        $filter->afwezig = true;
        $filter->applyTo($builder);
        $expected = 'SELECT FROM DagbestedingBundle\Entity\Traject traject LEFT JOIN traject.dagdelen dagdeel WITH dagdeel.datum >= :two_weeks_ago WHERE (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today) AND (dagdeel.id IS NULL OR dagdeel.aanwezigheid NOT IN (:aanwezig))';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(3, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('two_weeks_ago'));
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('aanwezig'));

        $builder = new QueryBuilder($em);
        $filter = new TrajectFilter();
        $filter->verlenging = true;
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today) AND traject.einddatum <= :two_months_from_now';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(2, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('two_months_from_now'));

        $builder = new QueryBuilder($em);
        $filter = new TrajectFilter();
        $filter->zonderOndersteuningsplan = true;
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE (traject.afsluitdatum IS NULL OR traject.afsluitdatum > :today) AND traject.startdatum <= :today AND (traject.ondersteuningsplanVerwerkt IS NULL OR traject.ondersteuningsplanVerwerkt = false)';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(1, $builder->getParameters());

        $builder = new QueryBuilder($em);
        $filter = new TrajectFilter();
        $filter->klant = $this->createMock(KlantFilter::class);
        $filter->klant->expects($this->once())->method('applyTo')->with($builder);
        $filter->applyTo($builder);

    }
}
