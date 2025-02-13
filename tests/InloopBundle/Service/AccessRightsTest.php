<?php

namespace Tests\InloopBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Land;
use AppBundle\Entity\Nationaliteit;
use AppBundle\Entity\Verblijfsstatus;
use Doctrine\ORM\EntityManagerInterface;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\AccessUpdater;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AccessRightsTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private AccessUpdater $accessUpdater;

    // Cache voor veel gebruikte entities
    private array $locatieCache = [];
    private array $verblijfsstatusCache = [];

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = static::getContainer()->get('doctrine')->getManager();
        $this->accessUpdater = static::getContainer()->get(AccessUpdater::class);

        // Vul caches voor betere performance
        $this->initializeEntityCaches();
    }

    private function initializeEntityCaches(): void
    {
        // Cache locaties
        $locaties = $this->em->getRepository(Locatie::class)->findAll();
        foreach ($locaties as $locatie) {
            $this->locatieCache[$locatie->getNaam()] = $locatie;
        }

        // Cache verblijfsstatussen
        $statussen = $this->em->getRepository(Verblijfsstatus::class)->findAll();
        foreach ($statussen as $status) {
            $this->verblijfsstatusCache[$status->getNaam()] = $status;
        }
    }

    /**
     * @test
     * @dataProvider fixtureBasedScenarios
     */
    public function verifyAccessRightsBasedOnKlantName(
        string $klantNaam,
        array $expectedLocaties,
        ?string $message = null
    ): void {
        // Arrange
        $klant = $this->em->getRepository(Klant::class)
            ->findOneBy(['naam' => $klantNaam]);

        $this->assertNotNull($klant, "Klant '$klantNaam' niet gevonden in fixtures");

        // Act
        $this->accessUpdater->updateForClient($klant);

        // Assert
        $actualLocaties = $this->getActualLocaties($klant);

        // Sort both arrays to ensure consistent comparison
        sort($expectedLocaties);
        sort($actualLocaties);

        $this->assertEquals(
            $expectedLocaties,
            $actualLocaties,
            $message ?? "Toegangsrechten voor klant '$klantNaam' komen niet overeen met verwachting"
        );
    }

    public function fixtureBasedScenarios(): array
    {
        return [
            'algemene_toegang' => [
                'klantNaam' => 'ToegangAlles',
                'verwachteLocaties' => ['Blaka Watra', 'De Kloof', 'Droogbak'],
                'message' => 'Klant met algemene toegang moet toegang hebben tot alle reguliere inlooplocaties'
            ],
            'geen_toegang' => [
                'klantNaam' => 'ToegangGeen',
                'verwachteLocaties' => ['Makom (winteropvang)', 'Slotermeerlaan (WKR)'],
                'message' => 'Klant zonder toegang mag alleen toegang hebben tot specifieke locaties'
            ],
            'specifieke_locatie_toegang' => [
                'klantNaam' => 'ToegangZeeburg',
                'verwachteLocaties' => ['Zeeburg'],
                'message' => 'Klant met specifieke locatie toegang mag alleen toegang hebben tot die locatie'
            ],
            'amoc_beide_toegang' => [
                'klantNaam' => 'ToegangAMOCBeide',
                'verwachteLocaties' => ['AMOC West', 'AMOC Stadhouderskade', 'Nachtopvang DRG'],
                'message' => 'Klant met AMOC beide toegang moet toegang hebben tot beide AMOC locaties en nachtopvang'
            ],
            'amoc_west_toegang' => [
                'klantNaam' => 'ToegangAMOCWest',
                'verwachteLocaties' => ['AMOC West', 'Nachtopvang DRG'],
                'message' => 'Klant met AMOC West toegang moet alleen toegang hebben tot AMOC West en nachtopvang'
            ],
            'villa_zaanstad_toegang' => [
                'klantNaam' => 'ToegangVillaZaanstad',
                'verwachteLocaties' => ['Villa Westerweide'],
                'message' => 'Klant met Villa Zaanstad toegang moet alleen toegang hebben tot Villa Westerweide'
            ],
            'gebruikersruimte_toegang' => [
                'klantNaam' => 'ToegangGebruikersruimte',
                'verwachteLocaties' => ['Blaka Watra Gebruikersruimte', 'Princehof Gebruikersruimte'],
                'message' => 'Klant met gebruikersruimte toegang moet toegang hebben tot alle gebruikersruimtes'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider edgeCaseScenarios
     */
    public function verifyAccessRightsEdgeCases(
        string $klantNaam,
        array $expectedLocaties,
        string $message
    ): void {
        $this->verifyAccessRightsBasedOnKlantName($klantNaam, $expectedLocaties, $message);
    }

    public function edgeCaseScenarios(): array
    {
        return [
            'verlopen_toegang' => [
                'klantNaam' => 'ToegangVerlopen',
                'verwachteLocaties' => [],
                'message' => 'Klant met verlopen toegang mag geen toegang meer hebben'
            ],
            'eu_burger_winteropvang' => [
                'klantNaam' => 'ToegangEUWinterOpvang',
                'verwachteLocaties' => ['Makom (winteropvang)', 'Slotermeerlaan (WKR)'],
                'message' => 'EU burger moet toegang hebben tot winteropvang locaties'
            ],
            'ongeldige_verblijfsstatus' => [
                'klantNaam' => 'ToegangOngeldigeStatus',
                'verwachteLocaties' => [],
                'message' => 'Klant met ongeldige verblijfsstatus mag geen toegang hebben'
            ],
            'combinatie_toegang' => [
                'klantNaam' => 'ToegangCombi',
                'verwachteLocaties' => ['AMOC West', 'Blaka Watra Gebruikersruimte'],
                'message' => 'Klant met combinatie van toegangsrechten moet toegang hebben tot alle relevante locaties'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider strategySpecificScenarios
     */
    public function verifyStrategySpecificCases(
        string $klantNaam,
        string $strategy,
        array $expectedLocaties,
        string $message
    ): void {
        // Arrange
        $klant = $this->em->getRepository(Klant::class)
            ->findOneBy(['naam' => $klantNaam]);

        $this->assertNotNull($klant, "Klant '$klantNaam' niet gevonden in fixtures");

        // Act
        $actualLocaties = $this->getStrategySpecificLocaties($klant, $strategy);

        // Assert
        sort($expectedLocaties);
        sort($actualLocaties);

        $this->assertEquals(
            $expectedLocaties,
            $actualLocaties,
            $message
        );
    }

    public function strategySpecificScenarios(): array
    {
        return [
            'amoc_strategy_roemeense_nationaliteit' => [
                'klantNaam' => 'ToegangAMOCWest',
                'strategy' => 'AmocStrategy',
                'verwachteLocaties' => ['AMOC West'],
                'message' => 'Roemeense klant moet via AMOC strategy toegang krijgen tot AMOC West'
            ],
            'gebruikersruimte_strategy_met_indicator' => [
                'klantNaam' => 'ToegangGebruikersruimte',
                'strategy' => 'GebruikersruimteStrategy',
                'verwachteLocaties' => ['Blaka Watra Gebruikersruimte', 'Princehof Gebruikersruimte'],
                'message' => 'Klant met gebruikersruimte indicator moet toegang krijgen tot alle gebruikersruimtes'
            ],
            'specifieke_locatie_strategy' => [
                'klantNaam' => 'ToegangZeeburg',
                'strategy' => 'SpecificLocationStrategy',
                'verwachteLocaties' => ['Zeeburg'],
                'message' => 'Klant met specifieke locatie moet alleen toegang krijgen tot die locatie'
            ],
            'villa_westerweide_strategy' => [
                'klantNaam' => 'ToegangVillaZaanstad',
                'strategy' => 'VillaWesterweideStrategy',
                'verwachteLocaties' => ['Villa Westerweide'],
                'message' => 'Klant met Villa Westerweide toegang moet alleen toegang krijgen via de Villa strategy'
            ],
            'winteropvang_eu_burgers_strategy' => [
                'klantNaam' => 'ToegangEUWinterOpvang',
                'strategy' => 'WinteropvangEUBurgersStrategy',
                'verwachteLocaties' => ['Makom (winteropvang)', 'Slotermeerlaan (WKR)'],
                'message' => 'EU burger moet via winteropvang strategy toegang krijgen tot winteropvang locaties'
            ]
        ];
    }

    private function getActualLocaties(Klant $klant): array
    {
        $result = $this->em->createQuery(
            'SELECT l.naam 
             FROM InloopBundle:Locatie l
             JOIN InloopBundle:Toegang t WITH t.locatie = l
             WHERE t.klant = :klant
             ORDER BY l.naam'
        )
            ->setParameter('klant', $klant)
            ->getResult();

        return array_column($result, 'naam');
    }

    private function getStrategySpecificLocaties(Klant $klant, string $strategy): array
    {
        // We simuleren hier de strategy specifieke logica
        // In een echte situatie zou je mogelijk de strategy classes direct willen testen
        switch ($strategy) {
            case 'AmocStrategy':
                return $this->getAmocStrategyLocaties($klant);
            case 'GebruikersruimteStrategy':
                return $this->getGebruikersruimteStrategyLocaties($klant);
            case 'SpecificLocationStrategy':
                return $this->getSpecificLocationStrategyLocaties($klant);
            case 'VillaWesterweideStrategy':
                return $this->getVillaWesterweideStrategyLocaties($klant);
            case 'WinteropvangEUBurgersStrategy':
                return $this->getWinteropvangEUBurgersStrategyLocaties($klant);
            default:
                throw new \InvalidArgumentException("Onbekende strategy: $strategy");
        }
    }

    private function getAmocStrategyLocaties(Klant $klant): array
    {
        // Eerst AccessUpdater laten werken
        $this->accessUpdater->updateForClient($klant);

        $result = $this->em->createQuery(
            'SELECT l.naam 
         FROM InloopBundle:Locatie l
         JOIN InloopBundle:Toegang t WITH t.locatie = l
         WHERE t.klant = :klant
         AND l.naam IN (:amoc_locaties)'
        )
            ->setParameter('klant', $klant)
            ->setParameter('amoc_locaties', ['AMOC West', 'AMOC Stadhouderskade'])
            ->getResult();

        return array_column($result, 'naam');
    }

    private function getGebruikersruimteStrategyLocaties(Klant $klant): array
    {
        // Eerst AccessUpdater laten werken
        $this->accessUpdater->updateForClient($klant);

        $result = $this->em->createQuery(
            'SELECT l.naam 
         FROM InloopBundle:Locatie l
         JOIN InloopBundle:Toegang t WITH t.locatie = l
         WHERE t.klant = :klant
         AND l.gebruikersruimte = true'
        )
            ->setParameter('klant', $klant)
            ->getResult();

        return array_column($result, 'naam');
    }

    private function getSpecificLocationStrategyLocaties(Klant $klant): array
    {
        // Eerst AccessUpdater laten werken
        $this->accessUpdater->updateForClient($klant);

        $result = $this->em->createQuery(
            'SELECT l.naam 
         FROM InloopBundle:Locatie l
         JOIN InloopBundle:Toegang t WITH t.locatie = l
         JOIN InloopBundle:Intake i WITH i = :intake
         JOIN i.specifiekeLocaties sl WITH l.id = sl.id
         WHERE t.klant = :klant'
        )
            ->setParameter('klant', $klant)
            ->setParameter('intake', $klant->getEersteIntake())
            ->getResult();

        return array_column($result, 'naam');
    }

    private function getVillaWesterweideStrategyLocaties(Klant $klant): array
    {
        // Eerst AccessUpdater laten werken
        $this->accessUpdater->updateForClient($klant);

        $result = $this->em->createQuery(
            'SELECT l.naam 
         FROM InloopBundle:Locatie l
         JOIN InloopBundle:Toegang t WITH t.locatie = l
         WHERE t.klant = :klant
         AND l.naam = :villa_naam'
        )
            ->setParameter('klant', $klant)
            ->setParameter('villa_naam', 'Villa Westerweide')
            ->getResult();

        return array_column($result, 'naam');
    }

    private function getWinteropvangEUBurgersStrategyLocaties(Klant $klant): array
    {
        // Eerst AccessUpdater laten werken
        $this->accessUpdater->updateForClient($klant);

        $result = $this->em->createQuery(
            'SELECT l.naam 
         FROM InloopBundle:Locatie l
         JOIN InloopBundle:Toegang t WITH t.locatie = l
         WHERE t.klant = :klant
         AND l.naam IN (:winteropvang_locaties)'
        )
            ->setParameter('klant', $klant)
            ->setParameter('winteropvang_locaties', ['Makom (winteropvang)', 'Slotermeerlaan (WKR)'])
            ->getResult();

        return array_column($result, 'naam');
    }
}