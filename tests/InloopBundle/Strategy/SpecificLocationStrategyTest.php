<?php

namespace Tests\InloopBundle\Strategy;

use AppBundle\Entity\Klant;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\LocatieDaoInterface;
use InloopBundle\Strategy\SpecificLocationStrategy;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class SpecificLocationStrategyTest extends DoctrineTestCase
{
    private SpecificLocationStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->strategy = new SpecificLocationStrategy();
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
            [new Locatie, true],
        ];
    }

    public function testBuildQuery()
    {
        $em = $this->createMock(EntityManager::class);
        $builder = (new QueryBuilder($em))->select('klant')->from(Klant::class, 'klant');

        // 'buildQuery' depends on a call to 'supports', so call that first
        $locatie = new Locatie();
        $this->strategy->supports($locatie);

        $this->strategy->buildQuery($builder);
        $expectedDQL = "SELECT klant
            FROM AppBundle\Entity\Klant klant
            LEFT JOIN eersteIntake.specifiekeLocaties specifiekeLocaties
            WHERE (eersteIntake.toegangInloophuis = true
                AND :locatie IN specifiekeLocaties)";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }
}
