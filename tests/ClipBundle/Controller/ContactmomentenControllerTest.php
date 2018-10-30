<?php

namespace Tests\ClipBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Medewerker;
use AppBundle\Test\WebTestCase;

class ContactmomentenControllerTest extends WebTestCase
{
    public function testSortColumns()
    {
        $medewerker = $this->getContainer()->get('AppBundle\Service\MedewerkerDao')->find('clip_user');
        $this->logIn($medewerker);

        $crawler = $this->client->request('GET', '/clip/contactmomenten/');

        $this->assertStatusCode(200, $this->client);
        $headers = $crawler->filter('tr th a');
        $this->assertGreaterThan(1, $headers->count());

        $headers->each(function ($header) {
            // @see https://github.com/KnpLabs/knp-components/issues/160
            $request = Request::create($header->link()->getUri());
            $_GET = $request->query->all();

            $this->client->click($header->link());
            $this->assertStatusCode(200, $this->client);
            $_GET = [];
        });
    }
}
