<?php

namespace Tests\InloopBundle\Strategy;

use AppBundle\Entity\Klant;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Strategy\GebruikersruimteStrategy;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class GebruikersruimteStrategyTest extends DoctrineTestCase
{
    private GebruikersruimteStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->strategy = $this->getContainer()->get(GebruikersruimteStrategy::class);
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
            [new Locatie(), false],
            [(new Locatie())->setGebruikersruimte(false), false],
            [(new Locatie())->setGebruikersruimte(true), true],
        ];
    }

    public function testBuildQuery()
    {
        $em = $this->createMock(EntityManager::class);
        $builder = (new QueryBuilder($em))->select('klant')->from(Klant::class, 'klant');

        $locatie = (new Locatie())->setGebruikersruimte(true);
        $this->strategy->buildQuery($builder, $locatie);
        // #FARHAD
        $expectedDQL = "SELECT klant
            FROM AppBundle\Entity\Klant klant
            INNER JOIN eaf.gebruikersruimte eersteIntakeGebruikersruimte
            LEFT JOIN klant.registraties registratie WITH registratie.locatie = :locatie_id
            LEFT JOIN InloopBundle\Entity\RecenteRegistratie recent WITH recent.klant = klant AND recent.locatie = :locatie_id
            LEFT JOIN recent.registratie recenteRegistratie WITH DATE(recenteRegistratie.buiten) > :two_months_ago
            WHERE eaf.toegangInloophuis = true
            GROUP BY klant.id
            HAVING COUNT(recenteRegistratie) > 0 OR COUNT(registratie.id) = 0 OR MAX(laatsteIntake.intakedatum) > :two_months_ago";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $builder->getDQL());
    }
}
