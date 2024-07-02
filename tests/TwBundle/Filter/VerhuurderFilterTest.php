<?php

namespace Tests\TwBundle\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;
use TwBundle\Entity\Verhuurder;
use TwBundle\Filter\VerhuurderFilter;

class VerhuurderFilterTest extends DoctrineTestCase
{
    public function testApplyTo()
    {
        $filter = new VerhuurderFilter();

        // empty filter
        $builder = $this->getQueryBuilder();
        $filter->applyTo($builder);
        $expected = 'SELECT FROM TwBundle\Entity\Verhuurder verhuurder WHERE verhuurder.id NOT IN(SELECT v.id
            FROM TwBundle\Entity\Verhuurder v
            INNER JOIN v.appKlant ak
            INNER JOIN v.huuraanbiedingen aanbod
            INNER JOIN aanbod.huurovereenkomst overeenkomst WITH
                overeenkomst.isReservering = false
                AND overeenkomst.startdatum IS NOT NULL
                AND (overeenkomst.afsluitdatum IS NULL OR overeenkomst.afsluitdatum > :today))';
        $this->assertEqualsIgnoringWhitespace($expected, (string) $builder);

        // gekoppeld
        $builder = $this->getQueryBuilder();
        $filter->gekoppeld = true;
        $filter->applyTo($builder);
        $expected = 'SELECT FROM TwBundle\Entity\Verhuurder verhuurder
            WHERE verhuurder.id IN(
                SELECT v.id
                FROM TwBundle\Entity\Verhuurder v
                INNER JOIN v.appKlant ak
                INNER JOIN v.huuraanbiedingen aanbod
                INNER JOIN aanbod.huurovereenkomst overeenkomst WITH
                    overeenkomst.isReservering = false
                    AND overeenkomst.startdatum IS NOT NULL
                    AND (overeenkomst.afsluitdatum IS NULL OR overeenkomst.afsluitdatum > :today)
                )';
        $this->assertEqualsIgnoringWhitespace($expected, (string) $builder);

        // niet gekoppeld
        $builder = $this->getQueryBuilder();
        $filter->gekoppeld = false;
        $filter->applyTo($builder);
        $expected = 'SELECT FROM TwBundle\Entity\Verhuurder verhuurder
            WHERE verhuurder.id NOT IN(
                SELECT v.id
                FROM TwBundle\Entity\Verhuurder v
                INNER JOIN v.appKlant ak
                INNER JOIN v.huuraanbiedingen aanbod
                INNER JOIN aanbod.huurovereenkomst overeenkomst WITH
                    overeenkomst.isReservering = false
                    AND overeenkomst.startdatum IS NOT NULL
                    AND (overeenkomst.afsluitdatum IS NULL OR overeenkomst.afsluitdatum > :today)
                )';
        $this->assertEqualsIgnoringWhitespace($expected, (string) $builder);
    }

    private function getQueryBuilder(): QueryBuilder
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return (new QueryBuilder($em))->from(Verhuurder::class, 'verhuurder');
    }
}
