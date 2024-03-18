<?php

declare(strict_types=1);

namespace Tests\GaBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\GroepErOpUit;
use GaBundle\Filter\ActiviteitFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class ActiviteitFilterTest extends DoctrineTestCase
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
                function (): ActiviteitFilter {
                    $filter = new ActiviteitFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): ActiviteitFilter {
                    $filter = new ActiviteitFilter();
                    $filter->naam = 'Test 123';

                    return $filter;
                },
                'SELECT WHERE activiteit.naam LIKE :naam_part_0
                    AND activiteit.naam LIKE :naam_part_1',
            ],
            [
                function (): ActiviteitFilter {
                    $filter = new ActiviteitFilter();
                    $filter->groep = new GroepErOpUit();

                    return $filter;
                },
                'SELECT WHERE groep = :groep',
            ],
            [
                function (): ActiviteitFilter {
                    $filter = new ActiviteitFilter();
                    $filter->datum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE activiteit.datum >= :datum_van AND activiteit.datum <= :datum_tot',
            ],
            [
                function (): ActiviteitFilter {
                    $filter = new ActiviteitFilter();
                    $filter->tijd = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE TIME(activiteit.datum) >= :tijd_van AND TIME(activiteit.datum) <= :tijd_tot',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
