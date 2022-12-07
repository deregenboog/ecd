<?php

namespace Tests\UhkBundle\Controller;

use AppBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class DeelnemersControllerTest extends WebTestCase
{
    public function testSortColumns()
    {
        $this->markTestSkipped();
//
//        $medewerker = $this->getContainer()->get('AppBundle\Service\MedewerkerDao')->findByUsername('uhk_user');
//        $this->logIn($medewerker);
//
//        $crawler = $this->client->request('GET', $this->getUrl('ga_klantdossiers_index'));
//
//        $this->assertStatusCode(200, $this->client);
//        $headers = $crawler->filter('table.table thead tr th a');
//        $this->assertGreaterThan(1, $headers->count());
//
//        $headers->each(function ($header) {
//            // @see https://github.com/KnpLabs/knp-components/issues/160
//            $request = Request::create($header->link()->getUri());
//            $_GET = $request->query->all();
//
//            $this->client->click($header->link());
//            $this->assertStatusCode(200, $this->client);
//        });
    }
}
