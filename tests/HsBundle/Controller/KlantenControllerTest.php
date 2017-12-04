<?php

namespace Tests\HsBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testKlantenIndexSorting()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hs/klanten/');

        $sortLinks = $crawler->filter('tr.sortable');
        $this->markTestSkipped();
//         $this->assertGreaterThan(0, $sortLinks->count());
    }
}
