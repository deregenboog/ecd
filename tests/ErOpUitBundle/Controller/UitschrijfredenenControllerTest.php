<?php

declare(strict_types=1);

namespace Tests\ErOpUitBundle\Controller;

use AppBundle\Service\MedewerkerDao;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class UitschrijfredenenControllerTest extends WebTestCase
{
    public function testUserHasNoAccessToUitschrijfredenenIndex()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('eou_user');
        $client->loginUser($medewerker);

        $client->request('GET', $this->getUrl('eropuit_uitschrijfredenen_index'));
        $this->assertStatusCode(403, $client);
    }

    public function testAdminHasAccessToUitschrijfredenenIndex()
    {
        $client = static::createClient();

        $medewerker = $this->getContainer()->get(MedewerkerDao::class)->findByUsername('eou_admin');
        $client->loginUser($medewerker);

        $client->request('GET', $this->getUrl('eropuit_uitschrijfredenen_index'));
        $this->assertStatusCode(200, $client);
    }
}
