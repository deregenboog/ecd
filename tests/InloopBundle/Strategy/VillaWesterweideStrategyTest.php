<?php

namespace Tests\InloopBundle\Strategy;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Strategy\VillaWesterweideStrategy;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class VillaWesterweideStrategyTest extends DoctrineTestCase
{
    private VillaWesterweideStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->strategy = $this->getContainer()->get(VillaWesterweideStrategy::class);
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
            [(new Locatie())->setNaam('Villa Westerweide'), true],
            [(new Locatie())->setNaam('AMOC Stadhouderskade'), false],
            [(new Locatie())->setNaam('AMOC West'), false],
            [(new Locatie())->setNaam('Nachtopvang DRG'), false],
        ];
    }

    public function testBuildQuery()
    {
        $em = $this->createMock(EntityManager::class);
        $builder = new QueryBuilder($em);

        $this->strategy->buildQuery($builder);
        $expectedDQL = 'SELECT
            WHERE (
                eersteIntake.toegangInloophuis = true
                AND eersteIntakeLocatie.naam IN (:toegestaneLocatiesVoorIntakelocatie)
            )';
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }
}
