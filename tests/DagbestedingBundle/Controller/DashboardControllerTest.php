<?php

declare(strict_types=1);

namespace Tests\DagbestedingBundle\Controller;

use Tests\AppBundle\PHPUnit\PantherWithCoverageTestCase;

class DashboardControllerTest extends PantherWithCoverageTestCase
{
    public function testDashboardPages()
    {
        $client = static::createPantherClient();

        $client->request('GET', '/login');
        $client->submitForm('Inloggen', [
            '_username' => 'dagbesteding_user',
            '_password' => 'dagbesteding_user',
        ]);

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
