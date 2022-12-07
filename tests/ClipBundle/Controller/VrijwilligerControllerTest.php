<?php

namespace Tests\ClipBundle\Controller;

use AppBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class VrijwilligerControllerTest extends WebTestCase
{
    public function testAccessDenied() {
        $medewerker = $this->getContainer()->get('AppBundle\Service\MedewerkerDao')->findByUsername('clip_user');
        $this->logIn($medewerker);

        $crawler = $this->client->request('GET', '/clip/vrijwilligers/');
        $this->assertStatusCode(403, $this->client);
    }

    public function testSortColumns()
    {
        $medewerker = $this->getContainer()->get('AppBundle\Service\MedewerkerDao')->findByUsername('clip_user');
        $this->logIn($medewerker, ['ROLE_CLIP_VRIJWILLIGERS']);

        $crawler = $this->client->request('GET', '/clip/vrijwilligers/');
        $this->assertStatusCode(200, $this->client);

        $headers = $crawler->filter('tr th a.sortable');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) {
            // @see https://github.com/KnpLabs/knp-components/issues/160
            $request = Request::create($header->link()->getUri());
            $_GET = $request->query->all();

            $this->client->click($header->link());
            $this->assertStatusCode(200, $this->client);
        });
    }
}
