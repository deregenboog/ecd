<?php

namespace Tests\DagbestedingBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use AppBundle\Entity\Medewerker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DomCrawler\Link;

class TrajectenControllerTest extends WebTestCase
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

    public function testSortColumns()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/dagbesteding/trajecten/');

        $headers = $crawler->filter('tr th a');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) use ($client) {
            // @see https://github.com/KnpLabs/knp-components/issues/160
            $request = Request::create($header->link()->getUri());
            $_GET = $request->query->all();

            $client->click($header->link());
            $this->assertStatusCode(200, $client);
            $_GET = [];
        });
    }
}
