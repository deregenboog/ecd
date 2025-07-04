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

        self::bootKernel();
        $this->strategy = $this->getContainer()->get(ToegangOverigStrategy::class);
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

        $this->strategy->buildQuery($builder, new Locatie());
        // #FARHAD
        $expectedDQL = "SELECT klant
            FROM AppBundle\Entity\Klant klant
            LEFT JOIN eaf.verblijfsstatus verblijfsstatus
            WHERE eaf.toegangInloophuis = true
                AND (
                    eersteIntakeLocatie.naam != :villa_westerweide
                    OR eersteIntakeLocatie.naam IS NULL
                )
                AND (
                    eaf.verblijfsstatus IS NULL
                    OR verblijfsstatus.naam != :niet_rechthebbend
                    OR (
                        verblijfsstatus.naam = :niet_rechthebbend
                        AND eaf.overigenToegangVan <= :today
                    )
                )";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }
}
