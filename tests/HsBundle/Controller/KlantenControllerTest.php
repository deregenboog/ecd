<?php

namespace Tests\HsBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testKlantenIndexSorting()
    {
        $this->markTestSkipped();

        $client = static::createClient();

        $crawler = $client->request('GET', '/hs/klanten/');
//         var_dump($crawler->filter('table tr')->eq(1)->filter('td')->eq(2)->html()); die;

        $sortLinks = $crawler->filter('tr.sortable_headers');
//         $this->assertGreaterThan(0, $sortLinks->count());
    }
}
