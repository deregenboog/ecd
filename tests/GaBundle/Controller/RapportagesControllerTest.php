<?php

namespace Tests\GaBundle\Controller;

use AppBundle\Test\WebTestCase;

class RapportagesControllerTest extends WebTestCase
{
    public function testShowReports()
    {
        $medewerker = $this->getContainer()->get(\AppBundle\Service\MedewerkerDao::class)->findByUsername('ga_user');
        $this->logIn($medewerker);

        $crawler = $this->client->request('GET', '/ga/rapportages/');

        $this->assertStatusCode(200, $this->client);
        $form = $crawler->selectButton('Rapport tonen')->form();

        $reports = $crawler->filter('select option');
        $this->assertGreaterThan(1, $reports->count());

        foreach ($reports as $report) {
            if ('' === $report->getAttribute('value')) {
                continue;
            }
            $form['rapportage[rapport]'] = $report->getAttribute('value');
            $crawler = $this->client->submit($form, []);
            $this->assertStatusCode(200, $this->client);
        }
    }
}
