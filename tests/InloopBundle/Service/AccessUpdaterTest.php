<?php

namespace Tests\InloopBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\LocatieType;
use InloopBundle\Service\AccessUpdater;
use InloopBundle\Service\KlantDao;
use InloopBundle\Service\KlantDaoInterface;
use InloopBundle\Service\LocatieDao;
use Tests\AppBundle\PHPUnit\DoctrineTestCase;

class AccessUpdaterTest extends DoctrineTestCase
{
    public const BASE_DQL = 'SELECT DISTINCT klant.id
        FROM AppBundle\Entity\Klant klant
        INNER JOIN klant.huidigeStatus status
        LEFT JOIN klant.intakes intake
        LEFT JOIN klant.geslacht geslacht
        LEFT JOIN klant.laatsteIntake laatsteIntake
        LEFT JOIN klant.eersteIntake eersteIntake
        LEFT JOIN laatsteIntake.intakelocatie laatsteIntakeLocatie
        LEFT JOIN laatsteIntake.gebruikersruimte gebruikersruimte
        LEFT JOIN eersteIntake.intakelocatie eersteIntakeLocatie';

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
    }

    public function testUpdateAll()
    {
        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getConnection', 'getFilters', 'getRepository'])
            ->getMock();

        $em->method('getRepository')->with(LocatieType::class)
            ->willReturn($this->createMock(EntityRepository::class));

        // Expect a delete statement to be executed, warpped in a transaction.
        $connection = $this->createMock(Connection::class);
        $connection->expects($this->once())->method('beginTransaction');
        $connection->expects($this->once())->method('executeQuery')
            ->with('DELETE FROM inloop_toegang');
        $connection->expects($this->once())->method('commit');
        $em->method('getConnection')->willReturn($connection);

        $locatie1 = new Locatie();
        $locatie2 = new Locatie();
        $locatieDao = $this->createMock(LocatieDao::class);
        $locatieDao->expects($this->once())->method('findAllActiveLocationsOfTypes')
            ->with(['Inloop', 'Nachtopvang'])->willReturn([$locatie1, $locatie2]);

        // Create AccessUpdater, but mock method updateForLocation (tested elsewhere).
        // Expect method updateForLocation to be called once for each location
        // returned by the LocatieDao instance.
        $updater = $this->getMockBuilder(AccessUpdater::class)
            ->setConstructorArgs([
                $em,
                $this->createMock(KlantDao::class),
                $locatieDao,
                $this->getContainer()->get(StrategyContainer::class)->getStrategies(),
            ])
            ->setMethods(['updateForLocation'])
            ->getMock();
        $updater->expects($this->exactly(2))->method('updateForLocation')
            ->withConsecutive([$locatie1], [$locatie2]);

        $updater->updateAll();
    }

    public function testUpdateForAmocLocation()
    {
        $em = $this->getEntityManager(
            [
                $this->equalToIgnoringWhitespace('DELETE FROM inloop_toegang
                    WHERE locatie_id = :locatie
                        AND klant_id NOT IN (:klanten)'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
            [
                $this->equalToIgnoringWhitespace('INSERT INTO inloop_toegang (klant_id, locatie_id)
                    SELECT id, :locatie
                    FROM klanten
                    WHERE id IN (:klanten)
                        AND id NOT IN (
                            SELECT klant_id
                            FROM inloop_toegang
                            WHERE locatie_id = :locatie
                        )'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
        );
        $klantDao = $this->getKlantDao(['id' => 11], ['id' => 22], ['id' => 33]);

        $updater = new AccessUpdater(
            $em,
            $klantDao,
            $this->createMock(LocatieDao::class),
            $this->getContainer()->get(StrategyContainer::class)->getStrategies()
        );

        $locatie = $this->getMockBuilder(Locatie::class)
            ->setMethods(['getId', 'isActief'])->getMock();
        $locatie->method('getId')->willReturn(654);
        $locatie->method('isActief')->willReturn(true);
        $locatie->setNaam('AMOC Stadhouderskade');
        $updater->updateForLocation($locatie);

        $expectedDQL = self::BASE_DQL."
            LEFT JOIN eersteIntake.specifiekeLocaties specifiekeLocaties
            WHERE (
                ((eersteIntake.toegangInloophuis = true AND :locatie IN (specifiekeLocaties)))
                OR (
                    (
                        eersteIntake.toegangInloophuis = true
                        AND (
                            eersteIntakeLocatie.naam = 'AMOC Stadhouderskade'
                            OR (
                                eersteIntakeLocatie.naam = 'AMOC West'
                                AND eersteIntake.intakedatum < :four_months_ago
                            )
                        )
                    )
                )
            )
            AND status INSTANCE OF InloopBundle\Entity\Aanmelding";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $klantDao->getBuilder()->getDQL());
    }

    public function testUpdateForAmocWestLocation()
    {
        $em = $this->getEntityManager(
            [
                $this->equalToIgnoringWhitespace('DELETE FROM inloop_toegang
                    WHERE locatie_id = :locatie
                        AND klant_id NOT IN (:klanten)'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
            [
                $this->equalToIgnoringWhitespace('INSERT INTO inloop_toegang (klant_id, locatie_id)
                    SELECT id, :locatie
                    FROM klanten
                    WHERE id IN (:klanten)
                        AND id NOT IN (
                            SELECT klant_id
                            FROM inloop_toegang
                            WHERE locatie_id = :locatie
                        )'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
        );
        $klantDao = $this->getKlantDao(['id' => 11], ['id' => 22], ['id' => 33]);

        $updater = new AccessUpdater(
            $em,
            $klantDao,
            $this->createMock(LocatieDao::class),
            $this->getContainer()->get(StrategyContainer::class)->getStrategies()
        );

        $locatie = $this->getMockBuilder(Locatie::class)
            ->setMethods(['getId', 'isActief'])->getMock();
        $locatie->method('getId')->willReturn(654);
        $locatie->method('isActief')->willReturn(true);
        $locatie->setNaam('AMOC West');
        $updater->updateForLocation($locatie);

        $expectedDQL = self::BASE_DQL."
            LEFT JOIN eersteIntake.specifiekeLocaties specifiekeLocaties
            WHERE (((eersteIntake.toegangInloophuis = true AND :locatie IN (specifiekeLocaties))) OR ((eersteIntake.toegangInloophuis = true AND eersteIntakeLocatie.naam IN (:toegestaneLocatiesVoorIntakelocatie))) OR ((eersteIntake.toegangInloophuis = true AND (eersteIntakeLocatie.naam = 'AMOC Stadhouderskade' OR (eersteIntakeLocatie.naam = 'AMOC West' AND eersteIntake.intakedatum < :four_months_ago))))) AND status INSTANCE OF InloopBundle\Entity\Aanmelding";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $klantDao->getBuilder()->getDQL());
    }

    public function testUpdateForGebruikersruimteLocation()
    {
        $em = $this->getEntityManager(
            [
                $this->equalToIgnoringWhitespace('DELETE FROM inloop_toegang
                    WHERE locatie_id = :locatie
                        AND klant_id NOT IN (:klanten)'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
            [
                $this->equalToIgnoringWhitespace('INSERT INTO inloop_toegang (klant_id, locatie_id)
                    SELECT id, :locatie
                    FROM klanten
                    WHERE id IN (:klanten)
                        AND id NOT IN (
                            SELECT klant_id
                            FROM inloop_toegang
                            WHERE locatie_id = :locatie
                        )'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
        );
        $klantDao = $this->getKlantDao(['id' => 11], ['id' => 22], ['id' => 33]);

        $updater = new AccessUpdater(
            $em,
            $klantDao,
            $this->createMock(LocatieDao::class),
            $this->getContainer()->get(StrategyContainer::class)->getStrategies()
        );

        $locatie = $this->getMockBuilder(Locatie::class)
            ->setMethods(['getId', 'isActief'])->getMock();
        $locatie->method('getId')->willReturn(654);
        $locatie->method('isActief')->willReturn(true);
        $locatie->setGebruikersruimte(true);
        $updater->updateForLocation($locatie);

        $expectedDQL = self::BASE_DQL."
            LEFT JOIN eersteIntake.specifiekeLocaties specifiekeLocaties
            INNER JOIN eersteIntake.gebruikersruimte eersteIntakeGebruikersruimte
            LEFT JOIN klant.registraties registratie WITH registratie.locatie = :locatie_id
            LEFT JOIN InloopBundle\Entity\RecenteRegistratie recent WITH recent.klant = klant AND recent.locatie = :locatie_id
            LEFT JOIN recent.registratie recenteRegistratie WITH DATE(recenteRegistratie.buiten) > :two_months_ago
            WHERE (
                ((eersteIntake.toegangInloophuis = true AND :locatie IN (specifiekeLocaties)))
                OR ((eersteIntake.toegangInloophuis = true AND eersteIntakeGebruikersruimte.id = :locatie_id))
            )
            AND status INSTANCE OF InloopBundle\Entity\Aanmelding
            GROUP BY klant.id
            HAVING COUNT(recenteRegistratie) > 0
            OR COUNT(registratie.id) = 0
            OR MAX(laatsteIntake.intakedatum) > :two_months_ago";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $klantDao->getBuilder()->getDQL());
    }

    public function testUpdateForVillaWesterweideLocation()
    {
        $em = $this->getEntityManager(
            [
                $this->equalToIgnoringWhitespace('DELETE FROM inloop_toegang
                    WHERE locatie_id = :locatie
                        AND klant_id NOT IN (:klanten)'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
            [
                $this->equalToIgnoringWhitespace('INSERT INTO inloop_toegang (klant_id, locatie_id)
                    SELECT id, :locatie
                    FROM klanten
                    WHERE id IN (:klanten)
                        AND id NOT IN (
                            SELECT klant_id
                            FROM inloop_toegang
                            WHERE locatie_id = :locatie
                        )'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
        );
        $klantDao = $this->getKlantDao(['id' => 11], ['id' => 22], ['id' => 33]);

        $updater = new AccessUpdater(
            $em,
            $klantDao,
            $this->createMock(LocatieDao::class),
            $this->getContainer()->get(StrategyContainer::class)->getStrategies()
        );

        $locatie = $this->getMockBuilder(Locatie::class)
            ->setMethods(['getId', 'isActief'])->getMock();
        $locatie->method('getId')->willReturn(654);
        $locatie->method('isActief')->willReturn(true);
        $locatie->setNaam('Villa Westerweide');
        $updater->updateForLocation($locatie);

        $expectedDQL = self::BASE_DQL."
            LEFT JOIN eersteIntake.specifiekeLocaties specifiekeLocaties
            WHERE (
                ((
                    eersteIntake.toegangInloophuis = true
                    AND :locatie IN (specifiekeLocaties)
                ))
                OR ((
                    eersteIntake.toegangInloophuis = true
                    AND eersteIntakeLocatie.naam IN (:toegestaneLocatiesVoorIntakelocatie)
                ))
            )
            AND status INSTANCE OF InloopBundle\Entity\Aanmelding";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $klantDao->getBuilder()->getDQL());
    }

    public function testUpdateForWinteropvangEUBurgersLocation()
    {
        $em = $this->getEntityManager(
            [
                $this->equalToIgnoringWhitespace('DELETE FROM inloop_toegang
                    WHERE locatie_id = :locatie
                        AND klant_id NOT IN (:klanten)'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
            [
                $this->equalToIgnoringWhitespace('INSERT INTO inloop_toegang (klant_id, locatie_id)
                    SELECT id, :locatie
                    FROM klanten
                    WHERE id IN (:klanten)
                        AND id NOT IN (
                            SELECT klant_id
                            FROM inloop_toegang
                            WHERE locatie_id = :locatie
                        )'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
        );
        $klantDao = $this->getKlantDao(['id' => 11], ['id' => 22], ['id' => 33]);

        $updater = new AccessUpdater(
            $em,
            $klantDao,
            $this->createMock(LocatieDao::class),
            $this->getContainer()->get(StrategyContainer::class)->getStrategies()
        );

        $locatie = $this->getMockBuilder(Locatie::class)
            ->setMethods(['getId', 'isActief', 'hasLocatieType'])->getMock();
        $locatie->method('getId')->willReturn(654);
        $locatie->method('isActief')->willReturn(true);
        $locatie->method('hasLocatieType')->willReturn(true);
        $updater->updateForLocation($locatie);

        $expectedDQL = self::BASE_DQL."
            LEFT JOIN eersteIntake.specifiekeLocaties specifiekeLocaties
            LEFT JOIN eersteIntake.verblijfsstatus verblijfsstatus
            WHERE (
                ((eersteIntake.toegangInloophuis = true AND :locatie IN (specifiekeLocaties)))
                OR huidigeStatus IS NOT NULL
                OR (
                    eersteIntake.toegangInloophuis = true
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
                    )
                )
            ) AND status INSTANCE OF InloopBundle\Entity\Aanmelding";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $klantDao->getBuilder()->getDQL());
    }

    public function testUpdateForToegangOverigLocation()
    {
        $em = $this->getEntityManager(
            [
                $this->equalToIgnoringWhitespace('DELETE FROM inloop_toegang
                    WHERE locatie_id = :locatie
                        AND klant_id NOT IN (:klanten)'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
            [
                $this->equalToIgnoringWhitespace('INSERT INTO inloop_toegang (klant_id, locatie_id)
                    SELECT id, :locatie
                    FROM klanten
                    WHERE id IN (:klanten)
                        AND id NOT IN (
                            SELECT klant_id
                            FROM inloop_toegang
                            WHERE locatie_id = :locatie
                        )'),
                ['locatie' => 654, 'klanten' => [11, 22, 33]],
            ],
        );
        $klantDao = $this->getKlantDao(['id' => 11], ['id' => 22], ['id' => 33]);

        $updater = new AccessUpdater(
            $em,
            $klantDao,
            $this->createMock(LocatieDao::class),
            $this->getContainer()->get(StrategyContainer::class)->getStrategies()
        );

        $locatie = $this->getMockBuilder(Locatie::class)
            ->setMethods(['getId', 'isActief'])->getMock();
        $locatie->method('getId')->willReturn(654);
        $locatie->method('isActief')->willReturn(true);
        $updater->updateForLocation($locatie);

        $expectedDQL = self::BASE_DQL."
            LEFT JOIN eersteIntake.specifiekeLocaties specifiekeLocaties
            LEFT JOIN eersteIntake.verblijfsstatus verblijfsstatus
            WHERE (
                ((
                    eersteIntake.toegangInloophuis = true
                    AND :locatie IN (specifiekeLocaties)
                ))
                OR (
                    eersteIntake.toegangInloophuis = true
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
                    )
                )
            )
            AND status INSTANCE OF InloopBundle\Entity\Aanmelding";
        $this->assertEqualsIgnoringWhitespace($expectedDQL, $klantDao->getBuilder()->getDQL());
    }

    public function testUpdateForNonActiveLocation()
    {
        $em = $this->getEntityManager(
            [
                $this->equalToIgnoringWhitespace('DELETE FROM inloop_toegang
                    WHERE locatie_id = :locatie'),
                ['locatie' => 654],
            ],
        );
        $klantDao = $this->getKlantDao();

        $updater = new AccessUpdater(
            $em,
            $klantDao,
            $this->createMock(LocatieDao::class),
            $this->getContainer()->get(StrategyContainer::class)->getStrategies()
        );

        $locatie = $this->getMockBuilder(Locatie::class)
            ->setMethods(['getId', 'isActief'])->getMock();
        $locatie->method('getId')->willReturn(654);
        $locatie->method('isActief')->willReturn(false);
        $updater->updateForLocation($locatie);
    }

    public function testUpdateForClientWithAccess()
    {
        $em = $this->getEntityManager(
            [
                $this->equalToIgnoringWhitespace('INSERT INTO inloop_toegang (klant_id, locatie_id)
                    VALUES (:klant, :locatie)'),
                ['locatie' => 987, 'klant' => 321],
            ],
            [
                $this->equalToIgnoringWhitespace('INSERT INTO inloop_toegang (klant_id, locatie_id)
                    VALUES (:klant, :locatie)'),
                ['locatie' => 654, 'klant' => 321],
            ],
        );
        $klantDao = $this->getKlantDao(['id' => 321], ['id' => 32], ['id' => 3]);

        $locatie1 = $this->createMock(Locatie::class);
        $locatie1->method('getId')->willReturn(987);
        $locatie2 = $this->createMock(Locatie::class);
        $locatie2->method('getId')->willReturn(654);
        $locatieDao = $this->createMock(LocatieDao::class);
        $locatieDao->expects($this->once())->method('findAllActiveLocationsOfTypes')
            ->with(['Inloop', 'Nachtopvang'])->willReturn([$locatie1, $locatie2]);

        $updater = new AccessUpdater(
            $em,
            $klantDao,
            $locatieDao,
            $this->getContainer()->get(StrategyContainer::class)->getStrategies()
        );

        $klant = $this->createMock(Klant::class);
        $klant->method('getId')->willReturn(321);
        $updater->updateForClient($klant);
    }

    public function testUpdateForClientWithoutAccess()
    {
        $em = $this->getEntityManager(
            [
                $this->equalToIgnoringWhitespace('DELETE FROM inloop_toegang
                    WHERE locatie_id = :locatie
                    AND klant_id = :klant'),
                ['locatie' => 987, 'klant' => 321],
            ],
            [
                $this->equalToIgnoringWhitespace('DELETE FROM inloop_toegang
                    WHERE locatie_id = :locatie
                    AND klant_id = :klant'),
                ['locatie' => 654, 'klant' => 321],
            ],
        );
        $klantDao = $this->getKlantDao(['id' => 123], ['id' => 12], ['id' => 1]);

        $locatie1 = $this->createMock(Locatie::class);
        $locatie1->method('getId')->willReturn(987);
        $locatie2 = $this->createMock(Locatie::class);
        $locatie2->method('getId')->willReturn(654);
        $locatieDao = $this->createMock(LocatieDao::class);
        $locatieDao->expects($this->once())->method('findAllActiveLocationsOfTypes')
            ->with(['Inloop', 'Nachtopvang'])->willReturn([$locatie1, $locatie2]);

        $updater = new AccessUpdater(
            $em,
            $klantDao,
            $locatieDao,
            $this->getContainer()->get(StrategyContainer::class)->getStrategies()
        );

        $klant = $this->createMock(Klant::class);
        $klant->method('getId')->willReturn(321);
        $updater->updateForClient($klant);
    }

    /**
     * Returns a mocked entity manager that expects the provided queries to be
     * executed on the entity manager's database connection.
     */
    private function getEntityManager(...$expectedQueries)
    {
        $em = $this->createMock(EntityManager::class);
        $em->method('getFilters')->willReturn($this->createMock(FilterCollection::class));

        // expect the entity manager's connection to execute this SQL
        $connection = $this->createMock(Connection::class);
        $connection->expects($this->exactly(count($expectedQueries)))->method('executeQuery')->withConsecutive(...$expectedQueries);
        $em->method('getConnection')->willReturn($connection);

        $locatieTypeRepository = $this->createMock(EntityRepository::class);
        $locatieTypeRepository->method('findOneBy')->with(['naam' => 'Nachtopvang'])
            ->willReturn((new LocatieType())->setNaam('Nachtopvang'));
        $em->method('getRepository')->with(LocatieType::class)
            ->willReturn($locatieTypeRepository);

        return $em;
    }

    /**
     * Returns a KlantDao instance with a mocked QueryBuilder that will return
     * the provided results. The QueryBuilder is exposed to test the DQL that
     * was built.
     */
    private function getKlantDao(...$klantIds): KlantDaoInterface
    {
        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['getExpressionBuilder'])
            ->getMock();

        $builder = $this->getMockBuilder(QueryBuilder::class)
            ->setConstructorArgs([$em])
            ->setMethods(['getQuery'])
            ->getMock();

        $klantRepository = new EntityRepository($em, new ClassMetadata(Klant::class));
        $em->method('getRepository')->with(Klant::class)
            ->willReturn($klantRepository);

        $query = $this->createMock(AbstractQuery::class);
        $query->method('getResult')->willReturn($klantIds);
        $builder->method('getQuery')->willReturn($query);
        $em->method('createQueryBuilder')->willReturn($builder);

        return new class($em) extends KlantDao {
            private QueryBuilder $builder;

            public function getBuilder(): QueryBuilder
            {
                return $this->builder;
            }

            /**
             * Makes the query builder accessible for later inspection.
             */
            public function getAllQueryBuilder(FilterInterface $filter = null)
            {
                $this->builder = parent::getAllQueryBuilder($filter);

                return $this->builder;
            }
        };
    }
}
