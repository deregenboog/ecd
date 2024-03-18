<?php

declare(strict_types=1);

namespace Tests\ClipBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use ClipBundle\Entity\Behandelaar;
use ClipBundle\Entity\Vraagsoort;
use ClipBundle\Filter\ClientFilter;
use ClipBundle\Filter\VraagFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class VraagFilterTest extends DoctrineTestCase
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
                function (): VraagFilter {
                    $filter = new VraagFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): VraagFilter {
                    $filter = new VraagFilter();
                    $filter->id = 123;

                    return $filter;
                },
                'SELECT WHERE vraag.id = :id',
            ],
            [
                function (): VraagFilter {
                    $filter = new VraagFilter();
                    $filter->startdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE vraag.startdatum >= :startdatum_van AND vraag.startdatum <= :startdatum_tot',
            ],
            [
                function (): VraagFilter {
                    $filter = new VraagFilter();
                    $filter->afsluitdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE vraag.afsluitdatum >= :afsluitdatum_van AND vraag.afsluitdatum <= :afsluitdatum_tot',
            ],
            [
                function (): VraagFilter {
                    $filter = new VraagFilter();
                    $filter->openstaand = true;

                    return $filter;
                },
                'SELECT WHERE vraag.afsluitdatum IS NULL OR vraag.afsluitdatum > :today',
            ],
            [
                function (): VraagFilter {
                    $filter = new VraagFilter();
                    $filter->soort = new Vraagsoort();

                    return $filter;
                },
                'SELECT WHERE vraag.soort = :vraagsoort',
            ],
            [
                function (): VraagFilter {
                    $filter = new VraagFilter();
                    $filter->behandelaar = new Behandelaar();

                    return $filter;
                },
                'SELECT WHERE vraag.behandelaar = :behandelaar',
            ],
            [
                function (): VraagFilter {
                    $filter = new VraagFilter();
                    $filter->client = $this->createMock(ClientFilter::class);
                    $filter->client->expects($this->once())->method('applyTo');

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): VraagFilter {
                    $filter = new VraagFilter();
                    $filter->hulpCollegaGezocht = true;

                    return $filter;
                },
                'SELECT WHERE vraag.hulpCollegaGezocht = :hulpGezocht',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
