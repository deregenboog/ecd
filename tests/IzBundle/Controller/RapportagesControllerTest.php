<?php

namespace Tests\IzBundle\Controller;

use AppBundle\DataFixtures\AppFixtures;
use IzBundle\DataFixtures\IzFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class RapportagesControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->loadFixtures([
            AppFixtures::class,
            IzFixtures::class,
        ]);
    }

    public function testShowReports()
    {
        $this->markTestSkipped('Add reports first');

        $client = $this->makeClient([
            'username' => 'admin',
            'password' => 'admin-password',
        ]);
        $crawler = $client->request('GET', '/iz/rapportages/');
        $this->assertStatusCode(200, $client);

        $form = $crawler->selectButton('rapportage_show')->form();
        $reports = $crawler->filter('select#rapportage_rapport option');
        $this->assertGreaterThan(1, $reports->count());

        foreach ($reports as $report) {
            if ('' === $report->getAttribute('value')) {
                continue;
            }
            $form['rapportage[rapport]'] = $report->getAttribute('value');
            $crawler = $client->submit($form);
            $this->assertStatusCode(200, $client);
        }
    }

    public function testDownloadReports()
    {
        $this->markTestSkipped('Add reports first');

        $client = $this->makeClient([
            'username' => 'admin',
            'password' => 'admin-password',
        ]);
        $crawler = $client->request('GET', '/iz/rapportages/');
        $this->assertStatusCode(200, $client);

        $form = $crawler->selectButton('rapportage_download')->form();
        $reports = $crawler->filter('select#rapportage_rapport option');
        $this->assertGreaterThan(1, $reports->count());

        foreach ($reports as $report) {
            if ('' === $report->getAttribute('value')) {
                continue;
            }
            $form['rapportage[rapport]'] = $report->getAttribute('value');
            $crawler = $client->submit($form);
            $this->assertStatusCode(200, $client);
        }
    }
}
