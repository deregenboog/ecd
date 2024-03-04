<?php

namespace Tests\InloopBundle\Strategy;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\LocatieType;
use InloopBundle\Strategy\WinteropvangEUBurgers;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class WinteropvangEUBurgersTest extends DoctrineTestCase
{
    private WinteropvangEUBurgers $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        $locTypeRepo = $this->createMock(EntityRepository::class);
        $locTypeRepo->method('findOneBy')
            ->with(['naam' => 'Nachtopvang'])
            ->willReturn(new LocatieType());

        $em = $this->getEntityManagerMock();
        $em->method('getRepository')
            ->with(LocatieType::class)
            ->willReturn($locTypeRepo);

        $this->strategy = new WinteropvangEUBurgers(
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
