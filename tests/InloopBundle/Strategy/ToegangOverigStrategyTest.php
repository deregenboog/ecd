<?php

namespace Tests\InloopBundle\Strategy;

use AppBundle\Entity\Klant;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Strategy\ToegangOverigStrategy;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class ToegangOverigStrategyTest extends DoctrineTestCase
{
    private ToegangOverigStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        $em = $this->getEntityManagerMock();
        $this->strategy = new ToegangOverigStrategy(
            [
                'amoc_stadhouderskade' => [
                    'AMOC Stadhouderskade',
                    'AMOC West',
                    'Nachtopvang DRG',
                ],
                'villa_westerweide' => [
                    'Villa Westerweide',
                ],
                'amoc_west' => [
                    'AMOC West',
                    'Nachtopvang DRG',
                ],
            ],
            'Europees Burger (Niet Nederlands)',
            $em
        );
    }

    /**
     * @dataProvider supportsDataProvider
     */
    public function testSupports(Locatie $locatie, bool $supported)
    {
        $this->assertEquals($supported, $this->strategy->supports($locatie));
    }

    public function supportsDataProvider()
    {
        return [
            [new Locatie(), true],
            [(new Locatie())->setNaam(''), true],
            [(new Locatie())->setNaam('<overigen>'), true],
            [(new Locatie())->setGebruikersruimte(true), false],
            [(new Locatie())->setNaam('AMOC Stadhouderskade'), false],
            [(new Locatie())->setNaam('AMOC West'), false],
            [(new Locatie())->setNaam('Nachtopvang DRG'), false],
            [(new Locatie())->setNaam('Villa Westerweide'), false],
        ];
    }

    public function testBuildQuery()
    {
        $em = $this->getEntityManagerMock();
        $builder = (new QueryBuilder($em))->select('klant')->from(Klant::class, 'klant');

        $this->strategy->buildQuery($builder);
        $expectedDQL = "SELECT klant
            FROM AppBundle\Entity\Klant klant
            LEFT JOIN eersteIntake.verblijfsstatus verblijfsstatus
            WHERE eersteIntake.toegangInloophuis = true
                AND (
                    eersteIntakeLocatie.naam != :villa_westerweide
                    OR eersteIntakeLocatie.naam IS NULL
                )
                AND (
                    eersteIntake.verblijfsstatus IS NULL
                    OR verblijfsstatus.naam != :niet_rechthebbend
                    OR (
                        verblijfsstatus.naam = :niet_rechthebbend
                        AND eersteIntake.overigenToegangVan <= :today
                    )
                )";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }
}
