<?php

declare(strict_types=1);

namespace Tests\HsBundle\Filter;

use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Filter\DienstverlenerFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class DienstverlenerFilterTest extends DoctrineTestCase
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
                function (): DienstverlenerFilter {
                    $filter = new DienstverlenerFilter();

                    return $filter;
                },
                'SELECT WHERE dienstverlener.actief = true',
            ],
            [
                function (): DienstverlenerFilter {
                    $filter = new DienstverlenerFilter();
                    $filter->klant = $this->createMock(KlantFilter::class);
                    $filter->klant->expects($this->once())->method('applyTo');

                    return $filter;
                },
                'SELECT WHERE dienstverlener.actief = true',
            ],
            [
                function (): DienstverlenerFilter {
                    $filter = new DienstverlenerFilter();
                    $filter->id = 123;

                    return $filter;
                },
                'SELECT WHERE dienstverlener.id = :dienstverlener_id
                    AND dienstverlener.actief = true',
            ],
            [
                function (): DienstverlenerFilter {
                    $filter = new DienstverlenerFilter();
                    $filter->hulpverlener = 'Dienstverlener 123';

                    return $filter;
                },
                "SELECT WHERE CONCAT_WS(' ', dienstverlener.naamHulpverlener, dienstverlener.organisatieHulpverlener) LIKE :dienstverlener_hulpverlener_part_0
                    AND CONCAT_WS(' ', dienstverlener.naamHulpverlener, dienstverlener.organisatieHulpverlener) LIKE :dienstverlener_hulpverlener_part_1
                    AND dienstverlener.actief = true",
            ],
            [
                function (): DienstverlenerFilter {
                    $filter = new DienstverlenerFilter();
                    $filter->rijbewijs = true;

                    return $filter;
                },
                'SELECT WHERE dienstverlener.rijbewijs = true
                    AND dienstverlener.actief = true',
            ],
            [
                function (): DienstverlenerFilter {
                    $filter = new DienstverlenerFilter();
                    $filter->status = DienstverlenerFilter::STATUS_NON_ACTIVE;

                    return $filter;
                },
                'SELECT WHERE dienstverlener.actief = false',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
