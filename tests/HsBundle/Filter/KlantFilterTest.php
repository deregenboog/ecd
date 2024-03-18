<?php

declare(strict_types=1);

namespace Tests\HsBundle\Filter;

use AppBundle\Entity\Werkgebied;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Filter\KlantFilter;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class KlantFilterTest extends DoctrineTestCase
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
                function (): KlantFilter {
                    $filter = new KlantFilter();

                    return $filter;
                },
                'SELECT',
            ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->id = 123;

                    return $filter;
                },
                'SELECT WHERE klant.id = :klant_id',
            ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->naam = 'Klant 123';

                    return $filter;
                },
                "SELECT WHERE CONCAT_WS(' ', klant.voornaam, klant.tussenvoegsel, klant.achternaam) LIKE :klant_naam_part_0
                    AND CONCAT_WS(' ', klant.voornaam, klant.tussenvoegsel, klant.achternaam) LIKE :klant_naam_part_1",
                ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->hulpverlener = 'Hulpverlener 123';

                    return $filter;
                },
                "SELECT WHERE CONCAT_WS(' ', klant.naamHulpverlener, klant.organisatieHulpverlener) LIKE :klant_hulpverlener_part_0
                    AND CONCAT_WS(' ', klant.naamHulpverlener, klant.organisatieHulpverlener) LIKE :klant_hulpverlener_part_1",
            ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->adres = 'Adres 123';

                    return $filter;
                },
                "SELECT WHERE CONCAT_WS(' ', klant.adres, klant.postcode, klant.plaats, klant.telefoon, klant.mobiel) LIKE :klant_adres_part_0
                    AND CONCAT_WS(' ', klant.adres, klant.postcode, klant.plaats, klant.telefoon, klant.mobiel) LIKE :klant_adres_part_1",
            ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->stadsdeel = new Werkgebied('Centrum');

                    return $filter;
                },
                'SELECT WHERE klant.werkgebied = :klant_stadsdeel',
            ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->status = KlantFilter::STATUS_GEEN_NIEUWE_KLUSSEN;

                    return $filter;
                },
                'SELECT WHERE klant.status = :status',
            ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->negatiefSaldo = true;

                    return $filter;
                },
                'SELECT WHERE klant.saldo < 0',
            ],
            [
                function (): KlantFilter {
                    $filter = new KlantFilter();
                    $filter->afwijkendFactuuradres = 1;

                    return $filter;
                },
                'SELECT WHERE klant.afwijkendFactuuradres = :afwijkendFactuuradres',
            ],
        ];
    }

    private function createQueryBuilder()
    {
        $em = $this->createMock(EntityManagerInterface::class);

        return new QueryBuilder($em);
    }
}
