<?php

declare(strict_types=1);

namespace Tests\HsBundle\Filter;

use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Filter\VrijwilligerFilter;
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
                'SELECT WHERE vrijwilliger.actief = true',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->vrijwilliger = $this->createMock(KlantFilter::class);
                    $filter->vrijwilliger->expects($this->once())->method('applyTo');

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.actief = true',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->id = 123;

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.id = :vrijwilliger_id
                    AND vrijwilliger.actief = true',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->hulpverlener = 'Vrijwilliger 123';

                    return $filter;
                },
                "SELECT WHERE CONCAT_WS(' ', vrijwilliger.naamHulpverlener, vrijwilliger.organisatieHulpverlener) LIKE :vrijwilliger_hulpverlener_part_0
                    AND CONCAT_WS(' ', vrijwilliger.naamHulpverlener, vrijwilliger.organisatieHulpverlener) LIKE :vrijwilliger_hulpverlener_part_1
                    AND vrijwilliger.actief = true",
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->rijbewijs = true;

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.rijbewijs = true
                    AND vrijwilliger.actief = true',
            ],
            [
                function (): VrijwilligerFilter {
                    $filter = new VrijwilligerFilter();
                    $filter->status = VrijwilligerFilter::STATUS_NON_ACTIVE;

                    return $filter;
                },
                'SELECT WHERE vrijwilliger.actief = false',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
