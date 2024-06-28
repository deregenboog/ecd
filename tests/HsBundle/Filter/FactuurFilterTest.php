<?php

declare(strict_types=1);

namespace Tests\HsBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Filter\FactuurFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class FactuurFilterTest extends DoctrineTestCase
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
                function (): FactuurFilter {
                    $filter = new FactuurFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): FactuurFilter {
                    $filter = new FactuurFilter();
                    $filter->nummer = '#123-456';

                    return $filter;
                },
                'SELECT WHERE factuur.nummer LIKE :nummer',
            ],
            [
                function (): FactuurFilter {
                    $filter = new FactuurFilter();
                    $filter->datum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE factuur.datum >= :datum_van
                    AND factuur.datum <= :datum_tot',
            ],
            [
                function (): FactuurFilter {
                    $filter = new FactuurFilter();
                    $filter->bedrag = 123.45;

                    return $filter;
                },
                'SELECT WHERE factuur.bedrag = :bedrag',
            ],
            [
                function (): FactuurFilter {
                    $filter = new FactuurFilter();
                    $filter->status = true;

                    return $filter;
                },
                'SELECT WHERE factuur.locked = :locked',
            ],
            [
                function (): FactuurFilter {
                    $filter = new FactuurFilter();
                    $filter->inbaar = false;

                    return $filter;
                },
                'SELECT WHERE factuur.oninbaar = :oninbaar',
            ],
            [
                function (): FactuurFilter {
                    $filter = new FactuurFilter();
                    $filter->negatiefSaldo = true;

                    return $filter;
                },
                'SELECT GROUP BY factuur
                    HAVING (factuur.bedrag - SUM(betaling.bedrag)) > 0
                    OR (factuur.bedrag > 0 AND COUNT(betaling) = 0)',
            ],
            [
                function (): FactuurFilter {
                    $filter = new FactuurFilter();
                    $filter->klant = $this->createMock(FactuurFilter::class);
                    $filter->klant->expects($this->once())->method('applyTo');

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): FactuurFilter {
                    $filter = new FactuurFilter();
                    $filter->metHerinnering = true;

                    return $filter;
                },
                'SELECT WHERE herinnering.id IS NOT NULL',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
