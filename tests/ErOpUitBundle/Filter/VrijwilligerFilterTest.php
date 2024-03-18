<?php

declare(strict_types=1);

namespace Tests\ErOpUitBundle\Filter;

use AppBundle\Filter\VrijwilligerFilter as AppVrijwilligerFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use ErOpUitBundle\Filter\VrijwilligerFilter;
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
                'SELECT WHERE vrijwilliger.inschrijfdatum <= :today
                    AND (vrijwilliger.uitschrijfdatum > :today OR vrijwilliger.uitschrijfdatum IS NULL)',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->vrijwilliger = $this->createMock(AppVrijwilligerFilter::class);
                    $filter->vrijwilliger->expects($this->once())->method('applyTo');

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.inschrijfdatum <= :today
                    AND (vrijwilliger.uitschrijfdatum > :today OR vrijwilliger.uitschrijfdatum IS NULL)',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->inschrijfdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.inschrijfdatum >= :vrijwilliger_inschrijfdatum_start
                    AND vrijwilliger.inschrijfdatum <= :vrijwilliger_inschrijfdatum_end
                    AND vrijwilliger.inschrijfdatum <= :today
                    AND (vrijwilliger.uitschrijfdatum > :today OR vrijwilliger.uitschrijfdatum IS NULL)',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->uitschrijfdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.uitschrijfdatum >= :vrijwilliger_uitschrijfdatum_start
                    AND vrijwilliger.uitschrijfdatum <= :vrijwilliger_uitschrijfdatum_end
                    AND vrijwilliger.inschrijfdatum <= :today
                    AND (vrijwilliger.uitschrijfdatum > :today OR vrijwilliger.uitschrijfdatum IS NULL)',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
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
