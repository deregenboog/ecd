<?php

namespace Tests\InloopBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Land;
use AppBundle\Entity\Nationaliteit;
use AppBundle\Entity\Verblijfsstatus;
use Doctrine\ORM\EntityManagerInterface;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\AccessUpdater;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccessRightsTest extends WebTestCase
{
    private EntityManagerInterface $em;
    private AccessUpdater $accessUpdater;
    protected $client;

    // Cache voor veel gebruikte entities
    private array $locatieCache = [];
    private array $verblijfsstatusCache = [];

    protected function setUp(): void
    {
        // Create a client to boot the kernel
        $this->client = static::createClient();

        // Get the container and services
        $container = static::getContainer();

        $this->em = $container->get('doctrine')->getManager();
        $this->accessUpdater = $container->get(AccessUpdater::class);

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

    private function getAccessRightsSource($klantNaam)
    {
        $klant = $this->em->getRepository(Klant::class)
            ->findOneBy(['roepnaam' => $klantNaam]);
        $this->assertNotNull($klant, "Klant met roepnaam '$klantNaam' niet gevonden in fixtures");
        return $klant;
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
        $klant = $this->getAccessRightsSource($klantNaam);
        $this->assertNotNull($klant, "Klant '$klantNaam' niet gevonden in fixtures");
        $this->accessUpdater->updateForClient($klant);
        $actualLocaties = $this->getActualLocaties($klant);
        
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
                'verwachteLocaties' => [
                    'AMOC West',
                    'Blaka Watra',
                    'De Eik',
                    'De Kloof',
                    'De Spreekbuis',
                    'Droogbak',
                    'Makom',
                    'Makom (winteropvang)',
                    'Oud West',
                    'Princehof Inloop',
                    'Slotermeerlaan (WKR)',
                    'Transformatorweg',
                    'Vrouwen Nacht Opvang',
                    'Zeeburg'
                ],
                'message' => 'Klant met algemene toegang moet toegang hebben tot alle inlooplocaties'
            ],
            'geen_toegang' => [
                'klantNaam' => 'ToegangGeen',
                'verwachteLocaties' => ['Makom (winteropvang)', 'Slotermeerlaan (WKR)'],
                'message' => 'Klant zonder toegang mag alleen toegang hebben tot specifieke locaties'
            ],
            'specifieke_locatie_toegang' => [
                'klantNaam' => 'ToegangZeeburg',
                'verwachteLocaties' => [
                    'AMOC Stadhouderskade',
                    'AMOC West',
                    'Makom (winteropvang)',
                    'Slotermeerlaan (WKR)',
                    'Zeeburg',
                ],
                'message' => 'Klant met specifieke locatie toegang mag alleen toegang hebben tot die locatie'
            ],
            'amoc_beide_toegang' => [
                'klantNaam' => 'ToegangAMOCStadhouderskade',
                'verwachteLocaties' => [
                    'AMOC West',
                    'AMOC Stadhouderskade',
                    'Makom (winteropvang)',
                    'Slotermeerlaan (WKR)',
                ],
                'message' => 'Klant met AMOC beide toegang moet toegang hebben tot beide AMOC locaties en nachtopvang'
            ],
            'amoc_west_toegang' => [
                'klantNaam' => 'ToegangAMOCWest',
                'verwachteLocaties' => [
                    'AMOC West',
                    'Makom (winteropvang)',
                    'Slotermeerlaan (WKR)',
                ],
                'message' => 'Klant met AMOC West toegang moet alleen toegang hebben tot AMOC West en nachtopvang'
            ],
            'villa_zaanstad_toegang' => [
                'klantNaam' => 'ToegangVillaZaanstad',
                'verwachteLocaties' => [
                    /* #FARHAD*/ 'AMOC West',
                    'Villa Westerweide',
                    'Makom (winteropvang)',
                    'Slotermeerlaan (WKR)',
                ],
                'message' => 'Klant met Villa Zaanstad toegang moet alleen toegang hebben tot Villa Westerweide'
            ],
            'gebruikersruimte_toegang' => [
                'klantNaam' => 'ToegangGebruikersruimte',
                'verwachteLocaties' => [
                    'AMOC Stadhouderskade',
                    'AMOC West',
                    'Amoc Gebruikersruimte',
                    'Blaka Watra',
                    'De Eik',
                    'De Kloof',
                    'De Spreekbuis',
                    'Droogbak',
                    'Makom',
                    'Makom (winteropvang)',
                    'Oud West',
                    'Princehof Inloop',
                    'Slotermeerlaan (WKR)',
                    'Transformatorweg',
                    'Vrouwen Nacht Opvang',
                    'Zeeburg',
                    /* #FARHAD */'Blaka Watra Gebruikersruimte',
                    /* #FARHAD */'Princehof Gebruikersruimte',
                ],
                'message' => 'Klant met gebruikersruimte toegang moet toegang hebben tot alle gebruikersruimtes'
            ]
        ];
    }

    private function getActualLocaties(Klant $klant): array
    {
        $result = $this->em->createQuery(
            'SELECT l.naam 
             FROM InloopBundle\Entity\Locatie l
             JOIN InloopBundle\Entity\Toegang t WITH t.locatie = l
             WHERE t.klant = :klant
             ORDER BY l.naam'
        )
            ->setParameter('klant', $klant)
            ->getResult();

        return array_column($result, 'naam');
    }
}