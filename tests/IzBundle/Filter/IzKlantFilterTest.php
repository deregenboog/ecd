<?php

declare(strict_types=1);

namespace Tests\IzBundle\Filter;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Project;
use IzBundle\Filter\IzKlantFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class IzKlantFilterTest extends DoctrineTestCase
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
                function (): IzKlantFilter {
                    $filter = new IzKlantFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): IzKlantFilter {
                    $filter = new IzKlantFilter();
                    $filter->actief = IzKlantFilter::ACTIEF_OOIT;
                    $filter->hulpvraagMedewerker = new Medewerker();

                    return $filter;
                },
                'SELECT WHERE hulpvraagMedewerker = :hulpvraagMedewerker',
            ],
            [
                function (): IzKlantFilter {
                    $filter = new IzKlantFilter();
                    $filter->actief = IzKlantFilter::ACTIEF_OOIT;
                    $filter->project = new Project();

                    return $filter;
                },
                'SELECT WHERE hulpvraag.project = :project',
            ],
            [
                function (): IzKlantFilter {
                    $filter = new IzKlantFilter();
                    $filter->actief = IzKlantFilter::ACTIEF_OOIT;
                    $filter->project = new Project();
                    $filter->hulpvraagMedewerker = new Medewerker();

                    return $filter;
                },
                'SELECT WHERE hulpvraag.project = :project
                    AND hulpvraagMedewerker = :hulpvraagMedewerker',
            ],
            [
                function (): IzKlantFilter {
                    $filter = new IzKlantFilter();
                    $filter->actief = IzKlantFilter::ACTIEF_NU;
                    $filter->hulpvraagMedewerker = new Medewerker();

                    return $filter;
                },
                'SELECT WHERE hulpvraagMedewerker = :hulpvraagMedewerker
                    AND (
                        hulpvraag.einddatum IS NULL
                        OR hulpvraag.einddatum > :now
                    )
                    AND (
                        hulpvraag.koppelingEinddatum IS NULL
                        OR hulpvraag.koppelingEinddatum > :now
                    )',
            ],
            [
                function (): IzKlantFilter {
                    $filter = new IzKlantFilter();
                    $filter->actief = IzKlantFilter::ACTIEF_NU;
                    $filter->project = new Project();

                    return $filter;
                },
                'SELECT WHERE hulpvraag.project = :project
                    AND (
                        hulpvraag.einddatum IS NULL
                        OR hulpvraag.einddatum > :now
                    )
                    AND (
                        hulpvraag.koppelingEinddatum IS NULL
                        OR hulpvraag.koppelingEinddatum > :now
                    )',
            ],
            [
                function (): IzKlantFilter {
                    $filter = new IzKlantFilter();
                    $filter->actief = IzKlantFilter::ACTIEF_NU;
                    $filter->hulpvraagMedewerker = new Medewerker();
                    $filter->project = new Project();

                    return $filter;
                },
                'SELECT WHERE hulpvraag.project = :project
                    AND (
                        hulpvraag.einddatum IS NULL
                        OR hulpvraag.einddatum > :now
                    )
                    AND (
                        hulpvraag.koppelingEinddatum IS NULL
                        OR hulpvraag.koppelingEinddatum > :now
                    )
                    AND hulpvraagMedewerker = :hulpvraagMedewerker
                    AND (
                        hulpvraag.einddatum IS NULL
                        OR hulpvraag.einddatum > :now
                    )
                    AND (
                        hulpvraag.koppelingEinddatum IS NULL
                        OR hulpvraag.koppelingEinddatum > :now
                    )',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
