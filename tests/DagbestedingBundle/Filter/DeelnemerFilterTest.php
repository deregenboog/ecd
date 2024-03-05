<?php

namespace Tests\DagbestedingBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use DagbestedingBundle\Entity\Deelnemer;
use DagbestedingBundle\Filter\DeelnemerFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class DeelnemerFilterTest extends TestCase
{
    public function testApplyTo()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $builder = new QueryBuilder($em);

        $filter = new DeelnemerFilter();
        $filter->id = 123;
        $filter->applyTo($builder);
        $this->assertEquals("SELECT WHERE deelnemer.id = :id AND ((deelnemer.afsluitdatum IS NULL OR deelnemer.afsluitdatum >= DATE('now')))", (string) $builder);
        $this->assertCount(1, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('id'));

        $builder->resetDQLParts();
        $filter->medewerker = new Medewerker();
        $filter->applyTo($builder);
        $this->assertEquals("SELECT WHERE deelnemer.id = :id AND deelnemer.medewerker = :medewerker AND ((deelnemer.afsluitdatum IS NULL OR deelnemer.afsluitdatum >= DATE('now')))", (string) $builder);
        $this->assertCount(2, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('medewerker'));

        $builder->resetDQLParts();
        $filter->aanmelddatum = new AppDateRangeModel(new \DateTime('2024-01-01'), new \DateTime('2024-02-27'));
        $filter->applyTo($builder);
        $this->assertEquals("SELECT WHERE deelnemer.id = :id AND deelnemer.medewerker = :medewerker AND deelnemer.aanmelddatum >= :aanmelddatum_van AND deelnemer.aanmelddatum <= :aanmelddatum_tot AND ((deelnemer.afsluitdatum IS NULL OR deelnemer.afsluitdatum >= DATE('now')))", (string) $builder);
        $this->assertCount(4, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('aanmelddatum_van'));
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('aanmelddatum_tot'));

        $builder->resetDQLParts();
        $filter->afsluitdatum = new AppDateRangeModel(new \DateTime('2024-01-01'), new \DateTime('2024-02-27'));
        $filter->applyTo($builder);
        $this->assertEquals("SELECT WHERE deelnemer.id = :id AND deelnemer.medewerker = :medewerker AND deelnemer.aanmelddatum >= :aanmelddatum_van AND deelnemer.aanmelddatum <= :aanmelddatum_tot AND deelnemer.afsluitdatum >= :afsluitdatum_van AND deelnemer.afsluitdatum <= :afsluitdatum_tot AND ((deelnemer.afsluitdatum IS NULL OR deelnemer.afsluitdatum >= DATE('now')))", (string) $builder);
        $this->assertCount(6, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('afsluitdatum_van'));
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('afsluitdatum_tot'));

        $builder->resetDQLParts();
        $filter->afsluitdatum = new AppDateRangeModel(new \DateTime('2024-01-01'), new \DateTime('2024-02-27'));
        $filter->applyTo($builder);
        $this->assertEquals("SELECT WHERE deelnemer.id = :id AND deelnemer.medewerker = :medewerker AND deelnemer.aanmelddatum >= :aanmelddatum_van AND deelnemer.aanmelddatum <= :aanmelddatum_tot AND deelnemer.afsluitdatum >= :afsluitdatum_van AND deelnemer.afsluitdatum <= :afsluitdatum_tot AND ((deelnemer.afsluitdatum IS NULL OR deelnemer.afsluitdatum >= DATE('now')))", (string) $builder);
        $this->assertCount(6, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('afsluitdatum_van'));
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('afsluitdatum_tot'));

        $builder->resetDQLParts();
        $filter->actief = false;
        $filter->applyTo($builder);
        $this->assertEquals('SELECT WHERE deelnemer.id = :id AND deelnemer.medewerker = :medewerker AND deelnemer.aanmelddatum >= :aanmelddatum_van AND deelnemer.aanmelddatum <= :aanmelddatum_tot AND deelnemer.afsluitdatum >= :afsluitdatum_van AND deelnemer.afsluitdatum <= :afsluitdatum_tot', (string) $builder);
        $this->assertCount(6, $builder->getParameters());

        $builder->resetDQLParts();
        $builder->select('deelnemer')->from(Deelnemer::class, 'deelnemer');
        $filter->zonderTraject = true;
        $filter->applyTo($builder);
        $this->assertEquals("SELECT deelnemer FROM DagbestedingBundle\Entity\Deelnemer deelnemer LEFT JOIN deelnemer.trajecten traject WHERE deelnemer.id = :id AND deelnemer.medewerker = :medewerker AND deelnemer.aanmelddatum >= :aanmelddatum_van AND deelnemer.aanmelddatum <= :aanmelddatum_tot AND deelnemer.afsluitdatum >= :afsluitdatum_van AND deelnemer.afsluitdatum <= :afsluitdatum_tot AND traject.id IS NULL", (string) $builder);
        $this->assertCount(6, $builder->getParameters());

        $builder->resetDQLParts();
        $filter->zonderTraject = false;
        $filter->klant = new KlantFilter();
        $filter->klant->id = 123;
        $filter->applyTo($builder);
        $this->assertEquals('SELECT WHERE deelnemer.id = :id AND deelnemer.medewerker = :medewerker AND deelnemer.aanmelddatum >= :aanmelddatum_van AND deelnemer.aanmelddatum <= :aanmelddatum_tot AND deelnemer.afsluitdatum >= :afsluitdatum_van AND deelnemer.afsluitdatum <= :afsluitdatum_tot AND klant.id = :klant_id', (string) $builder);
        $this->assertCount(7, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('klant_id'));
    }
}
