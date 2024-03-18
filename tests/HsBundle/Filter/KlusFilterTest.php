<?php

declare(strict_types=1);

namespace Tests\HsBundle\Filter;

use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Entity\Activiteit;
use HsBundle\Entity\Klus;
use HsBundle\Filter\KlusFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class KlusFilterTest extends DoctrineTestCase
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
                function (): KlusFilter {
                    $filter = new KlusFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): KlusFilter {
                    $filter = new KlusFilter();
                    $filter->id = 123;

                    return $filter;
                },
                'SELECT WHERE klus.id = :id',
            ],
            [
                function (): KlusFilter {
                    $filter = new KlusFilter();
                    $filter->startdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE klus.startdatum >= :startdatum_van
                    AND klus.startdatum <= :startdatum_tot',
            ],
            [
                function (): KlusFilter {
                    $filter = new KlusFilter();
                    $filter->einddatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE klus.einddatum >= :einddatum_van
                    AND klus.einddatum <= :einddatum_tot',
            ],
            [
                function (): KlusFilter {
                    $filter = new KlusFilter();
                    $filter->annuleringsdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE klus.annuleringsdatum >= :annuleringsdatum_van
                    AND klus.annuleringsdatum <= :annuleringsdatum_tot',
            ],
            [
                function (): KlusFilter {
                    $filter = new KlusFilter();
                    $filter->status = Klus::STATUS_IN_BEHANDELING;

                    return $filter;
                },
                'SELECT WHERE klus.status = :status',
            ],
            [
                function (): KlusFilter {
                    $filter = new KlusFilter();
                    $filter->klant = $this->createMock(KlusFilter::class);
                    $filter->klant->expects($this->once())->method('applyTo');

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): KlusFilter {
                    $filter = new KlusFilter();
                    $filter->activiteit = new Activiteit();

                    return $filter;
                },
                'SELECT WHERE activiteit = :activiteit',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
