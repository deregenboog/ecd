<?php

declare(strict_types=1);

namespace Tests\GaBundle\Filter;

use AppBundle\Entity\Stadsdeel;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Filter\GroepFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class GroepFilterTest extends DoctrineTestCase
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
                function (): GroepFilter {
                    $filter = new GroepFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): GroepFilter {
                    $filter = new GroepFilter();
                    $filter->naam = 'Test 123';

                    return $filter;
                },
                'SELECT WHERE groep.naam LIKE :naam_part_0
                    AND groep.naam LIKE :naam_part_1',
            ],
            [
                function (): GroepFilter {
                    $filter = new GroepFilter();
                    $filter->werkgebied = new Stadsdeel();

                    return $filter;
                },
                'SELECT WHERE groep.werkgebied = :werkgebied',
            ],
            [
                function (): GroepFilter {
                    $filter = new GroepFilter();
                    $filter->startdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE groep.startdatum >= :startdatum_van AND groep.startdatum <= :startdatum_tot',
            ],
            [
                function (): GroepFilter {
                    $filter = new GroepFilter();
                    $filter->einddatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE groep.einddatum >= :einddatum_van AND groep.einddatum <= :einddatum_tot',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
