<?php

namespace Tests\InloopBundle\Strategy;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Strategy\SpecificLocationStrategy;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class SpecificLocationStrategyTest extends DoctrineTestCase
{
    private SpecificLocationStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->strategy = $this->getContainer()->get(SpecificLocationStrategy::class);
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
        ];
    }

    public function testBuildQuery()
    {
        $em = $this->createMock(EntityManager::class);
        $builder = (new QueryBuilder($em))->select('klant')->from(Klant::class, 'klant');

        $this->strategy->buildQuery($builder, new Locatie());
        $expectedDQL = "SELECT klant
            FROM AppBundle\Entity\Klant klant
            LEFT JOIN eersteIntake.specifiekeLocaties specifiekeLocaties
            WHERE (eersteIntake.toegangInloophuis = true
                AND :locatie IN specifiekeLocaties)";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }
}
