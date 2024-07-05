<?php

declare(strict_types=1);

namespace Tests\ClipBundle\Filter;

use ClipBundle\Filter\LocatieFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class LocatieFilterTest extends DoctrineTestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testApplyTo(\Closure $filter, string $expectedDQL)
    {
        $builder = $this->createQueryBuilder();
        $filter()->applyTo($builder);
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }

    public function dataProvider()
    {
        return [
            [
                function (): LocatieFilter {
                    $filter = new LocatieFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): LocatieFilter {
                    $filter = new LocatieFilter();
                    $filter->actief = false;

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): LocatieFilter {
                    $filter = new LocatieFilter();
                    $filter->actief = true;

                    return $filter;
                },
                'SELECT WHERE locatie.datumVan <= :today
                    AND (locatie.datumTot >= :today OR locatie.datumTot IS NULL)',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
