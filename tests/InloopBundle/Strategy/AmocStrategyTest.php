<?php

namespace Tests\InloopBundle\Strategy;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Strategy\AmocStrategy;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;
use Tests\InloopBundle\Service\AccessUpdaterTest;

class AmocStrategyTest extends DoctrineTestCase
{
    private AmocStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->strategy = new AmocStrategy(
            AccessUpdaterTest::ACCESS_STRATEGIES,
            AccessUpdaterTest::AMOC_VERBLIJFSSTATUS,
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
            [(new Locatie())->setNaam('AMOC Stadhouderskade'), true],
            [(new Locatie())->setNaam('AMOC West'), true],
            [(new Locatie())->setNaam('Nachtopvang DRG'), true],
            [(new Locatie())->setNaam('Villa Westerweide'), false],
        ];
    }

    public function testBuildQuery()
    {
        $em = $this->createMock(EntityManager::class);
        $builder = new QueryBuilder($em);

        $this->strategy->buildQuery($builder);
        $expectedDQL = "SELECT WHERE ( eersteIntake.toegangInloophuis = true AND (eersteIntakeLocatie.naam = 'AMOC Stadhouderskade' OR (eersteIntakeLocatie.naam = 'AMOC West' AND eersteIntake.intakedatum < :sixmonthsago) ) )";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }
}
