<?php

declare(strict_types=1);

namespace Tests\HsBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Filter\BetalingFilter;
use HsBundle\Filter\FactuurFilter;
use HsBundle\Filter\KlantFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class BetalingFilterTest extends DoctrineTestCase
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
                function (): BetalingFilter {
                    $filter = new BetalingFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): BetalingFilter {
                    $filter = new BetalingFilter();
                    $filter->referentie = '#123-456';

                    return $filter;
                },
                'SELECT WHERE betaling.referentie LIKE :referentie',
            ],
            [
                function (): BetalingFilter {
                    $filter = new BetalingFilter();
                    $filter->datum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE betaling.datum >= :datum_van
                    AND betaling.datum <= :datum_tot',
            ],
            [
                function (): BetalingFilter {
                    $filter = new BetalingFilter();
                    $filter->bedrag = 123.45;

                    return $filter;
                },
                'SELECT WHERE betaling.bedrag = :bedrag',
            ],
            [
                function (): BetalingFilter {
                    $filter = new BetalingFilter();
                    $filter->factuur = $this->createMock(KlantFilter::class);
                    $filter->factuur->expects($this->once())->method('applyTo');

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): BetalingFilter {
                    $filter = new BetalingFilter();
                    $filter->klant = $this->createMock(FactuurFilter::class);
                    $filter->klant->expects($this->once())->method('applyTo');

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
