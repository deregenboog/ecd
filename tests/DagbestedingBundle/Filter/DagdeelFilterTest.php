<?php

namespace Tests\DagbestedingBundle\Filter;

use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use DagbestedingBundle\Filter\DagdeelFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class DagdeelFilterTest extends TestCase
{
    public function testApplyTo()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $builder = new QueryBuilder($em);

        $filter = new DagdeelFilter();
        $filter->dagdeel = 'ochtend';
        $filter->applyTo($builder);
        $this->assertEquals('SELECT WHERE dagdeel.dagdeel = :dagdeel', (string) $builder);
        $this->assertCount(1, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('dagdeel'));

        $builder->resetDQLParts();
        $filter->datum = new AppDateRangeModel(new \DateTime('2024-01-01'));
        $filter->applyTo($builder);
        $this->assertEquals('SELECT WHERE dagdeel.dagdeel = :dagdeel AND dagdeel.datum >= :datum_van', (string) $builder);
        $this->assertCount(2, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('datum_van'));

        $builder->resetDQLParts();
        $filter->datum = new AppDateRangeModel(new \DateTime('2024-01-01'), new \DateTime('2024-02-27'));
        $filter->applyTo($builder);
        $this->assertEquals('SELECT WHERE dagdeel.dagdeel = :dagdeel AND dagdeel.datum >= :datum_van AND dagdeel.datum <= :datum_tot', (string) $builder);
        $this->assertCount(3, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('datum_tot'));

        $builder->resetDQLParts();
        $filter->klant = new KlantFilter();
        $filter->klant->id = 123;
        $filter->applyTo($builder);
        $this->assertEquals('SELECT WHERE dagdeel.dagdeel = :dagdeel AND dagdeel.datum >= :datum_van AND dagdeel.datum <= :datum_tot AND klant.id = :klant_id', (string) $builder);
        $this->assertCount(4, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('klant_id'));
    }
}
