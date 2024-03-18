<?php

declare(strict_types=1);

namespace Tests\ClipBundle\Filter;

use AppBundle\Entity\Werkgebied;
use AppBundle\Form\Model\AppDateRangeModel;
use ClipBundle\Filter\ClientFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class ClientFilterTest extends DoctrineTestCase
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
                function (): ClientFilter {
                    $filter = new ClientFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): ClientFilter {
                    $filter = new ClientFilter();
                    $filter->id = 123;

                    return $filter;
                },
                'SELECT WHERE client.id = :id',
            ],
            [
                function (): ClientFilter {
                    $filter = new ClientFilter();
                    $filter->naam = 'Willem-Alexander Claus George Ferdinand';

                    return $filter;
                },
                "SELECT WHERE CONCAT_WS(' ', client.voornaam, client.roepnaam, client.tussenvoegsel, client.achternaam) LIKE :client_naam_part_0
                    AND CONCAT_WS(' ', client.voornaam, client.roepnaam, client.tussenvoegsel, client.achternaam) LIKE :client_naam_part_1
                    AND CONCAT_WS(' ', client.voornaam, client.roepnaam, client.tussenvoegsel, client.achternaam) LIKE :client_naam_part_2
                    AND CONCAT_WS(' ', client.voornaam, client.roepnaam, client.tussenvoegsel, client.achternaam) LIKE :client_naam_part_3",
                ],
            [
                function (): ClientFilter {
                    $filter = new ClientFilter();
                    $filter->stadsdeel = new Werkgebied('Centrum');

                    return $filter;
                },
                'SELECT WHERE client.werkgebied = :stadsdeel',
            ],
            [
                function (): ClientFilter {
                    $filter = new ClientFilter();
                    $filter->aanmelddatum = new AppDateRangeModel();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): ClientFilter {
                    $filter = new ClientFilter();
                    $filter->aanmelddatum = new AppDateRangeModel(new \DateTime());

                    return $filter;
                },
                'SELECT WHERE client.aanmelddatum >= :aanmelddatum_van',
            ],
            [
                function (): ClientFilter {
                    $filter = new ClientFilter();
                    $filter->aanmelddatum = new AppDateRangeModel(null, new \DateTime());

                    return $filter;
                },
                'SELECT WHERE client.aanmelddatum <= :aanmelddatum_tot',
            ],
            [
                function (): ClientFilter {
                    $filter = new ClientFilter();
                    $filter->aanmelddatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE client.aanmelddatum >= :aanmelddatum_van AND client.aanmelddatum <= :aanmelddatum_tot',
            ],
            [
                function (): ClientFilter {
                    $filter = new ClientFilter();
                    $filter->afsluitdatum = new AppDateRangeModel();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): ClientFilter {
                    $filter = new ClientFilter();
                    $filter->afsluitdatum = new AppDateRangeModel(new \DateTime());

                    return $filter;
                },
                'SELECT WHERE client.afsluitdatum >= :afsluitdatum_van',
            ],
            [
                function (): ClientFilter {
                    $filter = new ClientFilter();
                    $filter->afsluitdatum = new AppDateRangeModel(null, new \DateTime());

                    return $filter;
                },
                'SELECT WHERE client.afsluitdatum <= :afsluitdatum_tot',
            ],
            [
                function (): ClientFilter {
                    $filter = new ClientFilter();
                    $filter->afsluitdatum = new AppDateRangeModel(new \DateTime(), new \DateTime());

                    return $filter;
                },
                'SELECT WHERE client.afsluitdatum >= :afsluitdatum_van AND client.afsluitdatum <= :afsluitdatum_tot',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
