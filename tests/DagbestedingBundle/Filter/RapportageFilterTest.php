<?php

namespace Tests\DagbestedingBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use DagbestedingBundle\Filter\RapportageFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class RapportageFilterTest extends TestCase
{
    public function testApplyTo()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $filter = new RapportageFilter();

        $builder = new QueryBuilder($em);
        $filter->applyTo($builder);
        $expected = 'SELECT';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(0, $builder->getParameters());

        $builder = new QueryBuilder($em);
        $filter->datum = new AppDateRangeModel(new \DateTime('2024-01-01'));
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE rapportage.datum >= :datum_van';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(1, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('datum_van'));

        $builder = new QueryBuilder($em);
        $filter->datum = new AppDateRangeModel(null, new \DateTime('2024-01-27'));
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE rapportage.datum <= :datum_tot';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(1, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('datum_tot'));

        $builder = new QueryBuilder($em);
        $filter->datum = new AppDateRangeModel(new \DateTime('2024-01-01'), new \DateTime('2024-01-27'));
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE rapportage.datum >= :datum_van AND rapportage.datum <= :datum_tot';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(2, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('datum_van'));
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('datum_tot'));
    }
}
