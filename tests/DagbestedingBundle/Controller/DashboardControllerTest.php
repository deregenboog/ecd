<?php

declare(strict_types=1);

namespace Tests\DagbestedingBundle\Controller;


use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;


class DashboardControllerTest extends WebTestCase
{

    public function testIndexPages()
    {
        $client = static::createClient();

        $user = static::getContainer()
            ->get(\AppBundle\Service\MedewerkerDao::class)
            ->findByUsername('dagbesteding_user');
        $client->loginUser($user);

        $crawler = $client->request(Request::METHOD_GET, '/dagbesteding/mijn/trajecten');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/dagbesteding/mijn/trajecten');
        $this->assertSelectorTextContains('h2', 'Actieve trajecten');

        $client->request('GET', '/dagbesteding/mijn/afwezigen');
        $this->assertSelectorTextContains('h2', 'Afwezigen');

        $client->request('GET', '/dagbesteding/mijn/verlengingen');
        $this->assertSelectorTextContains('h2', 'Aanstaande verlengingen');

        $client->request('GET', '/dagbesteding/mijn/ondersteuningsplan');
        $this->assertSelectorTextContains('h2', 'Zonder ondersteuningsplan');

    }

}
