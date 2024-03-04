<?php

namespace Tests\InloopBundle\Strategy;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Strategy\WinteropvangEUBurgersStrategy;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class WinteropvangEUBurgersStrategyTest extends DoctrineTestCase
{
    private WinteropvangEUBurgersStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->strategy = $this->getContainer()->get(WinteropvangEUBurgersStrategy::class);
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
        $nachtopvang = $this->createMock(Locatie::class);
        $nachtopvang->method('hasLocatieType')->willReturn(true);

        $inloop = $this->createMock(Locatie::class);
        $inloop->method('hasLocatieType')->willReturn(false);

        return [
            [$nachtopvang, true],
            [$inloop, false],
        ];
    }

    public function testBuildQuery()
    {
        $em = $this->createMock(EntityManager::class);
        $builder = new QueryBuilder($em);

        $this->strategy->buildQuery($builder);
        $expectedDQL = 'SELECT WHERE huidigeStatus IS NOT NULL';
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }
}
