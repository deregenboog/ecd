<?php

declare(strict_types=1);

namespace Tests\ErOpUitBundle\Filter;

use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use ErOpUitBundle\Filter\KlantFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class KlantFilterTest extends DoctrineTestCase
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
                function (): KlantFilter {
                    $filter = new KlantFilter();

                    return $filter;
                },
                'SELECT WHERE klant.inschrijfdatum <= :today
                    AND (klant.uitschrijfdatum > :today OR klant.uitschrijfdatum IS NULL)',
            ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->klant = $this->createMock(AppKlantFilter::class);
                    $filter->klant->expects($this->once())->method('applyTo');

                    return $filter;
                },
                'SELECT WHERE klant.inschrijfdatum <= :today
                    AND (klant.uitschrijfdatum > :today OR klant.uitschrijfdatum IS NULL)',
            ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->inschrijfdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE klant.inschrijfdatum >= :klant_inschrijfdatum_start
                    AND klant.inschrijfdatum <= :klant_inschrijfdatum_end
                    AND klant.inschrijfdatum <= :today
                    AND (klant.uitschrijfdatum > :today OR klant.uitschrijfdatum IS NULL)',
            ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->uitschrijfdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE klant.uitschrijfdatum >= :klant_uitschrijfdatum_start
                    AND klant.uitschrijfdatum <= :klant_uitschrijfdatum_end
                    AND klant.inschrijfdatum <= :today
                    AND (klant.uitschrijfdatum > :today OR klant.uitschrijfdatum IS NULL)',
            ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->actief = false;

                    return $filter;
                },
                'SELECT',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
