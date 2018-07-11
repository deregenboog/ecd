<?php

namespace Tests\GaBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class RapportagesControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->loadFixtures([]);

        $_SESSION['Auth'] = [
            'Medewerker' => [
                'id' => 1,
                'username' => 'bhuttinga',
                'uidnumber' => 1,
                'Group' => [
                    'CN=ECD Trajectbegeleider,CN=Users,DC=cluster,DC=deregenboog',
                    'CN=ECD Homeservice Beheer,CN=Users,DC=cluster,DC=deregenboog',
                    'CN=ECD Homeservice,CN=Users,DC=cluster,DC=deregenboog',
                    'CN=ECD Admin,CN=Users,DC=cluster,DC=deregenboog',
                ],
                'LdapUser' => [
                    'displayname' => 'Bart Huttinga',
                    'givenname' => 'Bart Huttinga',
                    'sn' => 'Bart Huttinga',
                    'uidnumber' => 1,
                    'mail' => 'bhuttinga@deregenboog.org',
                ],
            ],
        ];
    }

    public function testShowReports()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/ga/rapportages/');

        $this->assertStatusCode(200, $client);
        $form = $crawler->selectButton('Rapport tonen')->form();

        $reports = $crawler->filter('select option');
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
