<?php

namespace Tests\InloopBundle\Strategy;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Strategy\AmocWestStrategy;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class AmocWestStrategyTest extends DoctrineTestCase
{
    private AmocWestStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->strategy = $this->getContainer()->get(AmocWestStrategy::class);
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
            [(new Locatie())->setNaam('AMOC Stadhouderskade'), false],
            [(new Locatie())->setNaam('AMOC West'), true],
            [(new Locatie())->setNaam('Nachtopvang DRG'), true],
            [(new Locatie())->setNaam('Villa Westerweide'), false],
        ];
    }

    public function testBuildQuery()
    {
        $em = $this->createMock(EntityManager::class);
        $builder = new QueryBuilder($em);

        $this->strategy->buildQuery($builder, new Locatie());
        // #FARHAD
        $expectedDQL = 'SELECT
            WHERE (
                eaf.toegangInloophuis = true
                AND eersteIntakeLocatie.naam IN (:toegestaneLocatiesVoorIntakelocatie)
            )';
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }
}
