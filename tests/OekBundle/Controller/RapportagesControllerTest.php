<?php

namespace Tests\OekBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class RapportagesControllerTest extends WebTestCase
{
    public function testShowReports()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('oek_user');
        $client->loginUser($medewerker);

        $crawler = $client->request('GET', '/oek/rapportages/');
        $this->assertStatusCode(200, $client);

        $form = $crawler->selectButton('Rapport tonen')->form();
        $reports = $crawler->filter('select option');
        $this->assertGreaterThan(1, $reports->count());

        foreach ($reports as $report) {
            $value = $report->getAttribute('value');
            if ($value) {
                $form['rapportage[rapport]'] = $value;
                $crawler = $client->submit($form);
                $this->assertStatusCode(200, $client);
            }
        }
    }
}
