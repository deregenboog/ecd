<?php

declare(strict_types=1);

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class DashboardControllerTest extends WebTestCase
{
    /**
     * @dataProvider userMenuItems
     */
    public function testIndexPages(string $username, array $expectedMenuItems)
    {
        $client = static::createClient();

        $admin = static::getContainer()
            ->get(\AppBundle\Service\MedewerkerDao::class)
            ->findByUsername($username);
        $client->loginUser($admin);

        $crawler = $client->request(Request::METHOD_GET, '/');
        $this->assertResponseIsSuccessful();

        $menuItems = [];
        foreach ($crawler->filter('nav.navbar-default ul.navbar-nav > li > a') as $node) {
            $menuItems[] = trim(preg_replace('/[^-\s[:word:]]/', '', $node->textContent));
        }
        $this->assertEquals($expectedMenuItems, $menuItems);
    }

    public function userMenuItems()
    {
        return [
            [
                'admin',
                [
                    'Klanten',
                    'Vrijwilligers',
                    'Medewerkers',
                    'Rapportages',
                    'Inloophuizen',
                    'Oekraine',
                    'Maatschappelijk werk',
                    'Buurtboerderij',
                    'CLIP',
                    'Dagbesteding',
                    'ErOpUit-kalender',
                    'Groepsactiviteiten',
                    'Homeservice',
                    'Informele zorg',
                    'Tijdelijk wonen',
                    'Op eigen kracht',
                    'PFO',
                    'Uit het Krijt',
                    'Villa',
                    'Beheer',
                ],
            ],
            [
                'clip_user',
                ['CLIP'],
            ],
            [
                'clip_admin',
                ['Vrijwilligers', 'CLIP'],
            ],
            [
                'dagbesteding_user',
                ['Dagbesteding'],
            ],
            [
                'dagbesteding_admin',
                ['Dagbesteding'],
            ],
            [
                'eou_user',
                ['ErOpUit-kalender'],
            ],
            [
                'eou_admin',
                ['ErOpUit-kalender'],
            ],
            [
                'ga_user',
                ['Klanten', 'Vrijwilligers', 'ErOpUit-kalender', 'Groepsactiviteiten'],
            ],
            [
                'ga_admin',
                ['Klanten', 'Vrijwilligers', 'ErOpUit-kalender', 'Groepsactiviteiten'],
            ],
            [
                'hs_user',
                ['Klanten', 'Vrijwilligers', 'Homeservice'],
            ],
            [
                'inloop_user',
                ['Klanten', 'Inloophuizen'],
            ],
            [
                'iz_user',
                ['Klanten', 'Vrijwilligers', 'ErOpUit-kalender', 'Informele zorg'],
            ],
            [
                'tw_user',
                ['Klanten', 'Vrijwilligers', 'Tijdelijk wonen'],
            ],
            [
                'oek_user',
                ['Op eigen kracht'],
            ],
            [
                'villa_user',
                ['Vrijwilligers', 'Villa'],
            ],
        ];
    }
}
