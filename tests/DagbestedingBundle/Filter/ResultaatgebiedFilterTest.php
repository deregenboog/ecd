<?php

namespace Tests\DagbestedingBundle\Filter;

use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use DagbestedingBundle\Filter\ResultaatgebiedFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class ResultaatgebiedFilterTest extends TestCase
{
    public function testApplyTo()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $builder = new QueryBuilder($em);

        $filter = new ResultaatgebiedFilter();
        $filter->applyTo($builder);
        $expected = 'SELECT';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(0, $builder->getParameters());

        $builder->resetDQLParts();
        $filter->soort = new Resultaatgebiedsoort();
        $filter->applyTo($builder);
        $expected = 'SELECT WHERE resultaatgebied.soort = :soort';
        $this->assertEquals($expected, (string) $builder);
        $this->assertCount(1, $builder->getParameters());
        $this->assertInstanceOf(Parameter::class, $builder->getParameter('soort'));
    }
}
