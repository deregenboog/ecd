<?php

declare(strict_types=1);

namespace Tests\BuurtboerderijBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use BuurtboerderijBundle\Filter\VrijwilligerFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class VrijwilligerFilterTest extends DoctrineTestCase
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
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->vrijwilliger = $this->createMock(VrijwilligerFilter::class);
                    $filter->vrijwilliger->expects($this->once())->method('applyTo');

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->aanmelddatum = new AppDateRangeModel(new \DateTime());

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.aanmelddatum >= :aanmelddatum_start',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->aanmelddatum = new AppDateRangeModel(null, new \DateTime());

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.aanmelddatum <= :aanmelddatum_end',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->aanmelddatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.aanmelddatum >= :aanmelddatum_start AND vrijwilliger.aanmelddatum <= :aanmelddatum_end',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->afsluitdatum = new AppDateRangeModel(new \DateTime());

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.afsluitdatum >= :afsluitdatum_start',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->afsluitdatum = new AppDateRangeModel(null, new \DateTime());

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.afsluitdatum <= :afsluitdatum_end',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->afsluitdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.afsluitdatum >= :afsluitdatum_start AND vrijwilliger.afsluitdatum <= :afsluitdatum_end',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
