<?php

namespace Tests\TwBundle\Filter;

use Doctrine\ORM\EntityManagerInterface;
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
        $expected = 'SELECT FROM TwBundle\Entity\Verhuurder verhuurder';
        $this->assertEquals($expected, (string) $builder);

        // gekoppeld
        $builder = $this->getQueryBuilder();
        $filter->gekoppeld = true;
        $filter->applyTo($builder);
        $expected = 'SELECT FROM TwBundle\Entity\Verhuurder verhuurder
            LEFT JOIN verhuurder.huuraanbiedingen huuraanbod WITH huuraanbod.afsluiting IS NULL
            LEFT JOIN huuraanbod.huurovereenkomst huurovereenkomst WITH
                huurovereenkomst.isReservering = false
                AND huurovereenkomst.startdatum IS NOT NULL
                AND (huurovereenkomst.afsluitdatum IS NULL OR huurovereenkomst.afsluitdatum > :today)
            WHERE huurovereenkomst IS NOT NULL';
        $this->assertEqualsIgnoringWhitespace($expected, (string) $builder);

        // niet gekoppeld
        $builder = $this->getQueryBuilder();
        $filter->gekoppeld = false;
        $filter->applyTo($builder);
        $expected = 'SELECT FROM TwBundle\Entity\Verhuurder verhuurder
            LEFT JOIN verhuurder.huuraanbiedingen huuraanbod WITH huuraanbod.afsluiting IS NULL
            LEFT JOIN huuraanbod.huurovereenkomst huurovereenkomst WITH
                huurovereenkomst.isReservering = false
                AND huurovereenkomst.startdatum IS NOT NULL
                AND (huurovereenkomst.afsluitdatum IS NULL OR huurovereenkomst.afsluitdatum > :today)
            WHERE huurovereenkomst IS NULL';
        $this->assertEqualsIgnoringWhitespace($expected, (string) $builder);
    }

    private function getQueryBuilder(): QueryBuilder
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return (new QueryBuilder($em))->from(Verhuurder::class, 'verhuurder');
    }
}
