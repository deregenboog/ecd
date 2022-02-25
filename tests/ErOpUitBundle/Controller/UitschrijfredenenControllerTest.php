<?php

namespace Tests\ErOpUitBundle\Controller;

use AppBundle\Test\WebTestCase;

class UitschrijfredenenControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $medewerker = $this->getContainer()->get(\AppBundle\Service\MedewerkerDao::class)->find('ga_user');
        $this->logIn($medewerker, 'ROLE_EROPUIT');
        $this->client->request('GET', $this->getUrl('eropuit_uitschrijfredenen_index'));
        $this->assertStatusCode(403, $this->client);

        $medewerker = $this->getContainer()->get(\AppBundle\Service\MedewerkerDao::class)->find('ga_admin');
        $this->logIn($medewerker, 'ROLE_EROPUIT_BEHEER');
        $this->client->request('GET', $this->getUrl('eropuit_uitschrijfredenen_index'));
        $this->assertStatusCode(200, $this->client);
    }
}
