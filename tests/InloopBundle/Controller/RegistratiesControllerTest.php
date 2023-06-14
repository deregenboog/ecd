<?php

namespace Tests\InloopBundle\Controller;

use AppBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class RegistratiesControllerTest extends WebTestCase
{
    public function testToegangInloophuis()
    {
        $medewerker = $this->getContainer()->get(\AppBundle\Service\MedewerkerDao::class)->findByUsername('inloop_user');
        $this->client->loginUser($medewerker);

        /*
         * Request edit of intake and submit form to trigger events for access 'calculation'.
         *
         * First 5 intakes are custom made in fixtures so they represent toegang data which matches several cases which is cheked against.
         */
        foreach ([1, 2, 3, 4, 5] as $value) {
            $this->editToegang($value);
        }

        /*
         * Blaka Watra, De Eik, De Kloof, De Spreekbuis, Derde Schinkel, Droogbak, Makom, Noorderpark, Oud West, Penitentiaire Inrichting, Politie, Princehof Inloop, Valentijn, Vrouwen Nacht Opvang, Westerpark
         */
        $this->checkIntakeOnDiensten(1, '//a[text()[contains(.,"Blaka Watra, De Eik, De Kloof")]]');

        /*
         * AMOC, Nachtopvang DRG
         */
        $this->checkIntakeOnDiensten(2, '//a[text()[contains(.,"AMOC, Nachtopvang DRG")]]');

        /*
         * Transformatorweg, Zeeburg
         */
        $this->checkIntakeOnDiensten(3, '//a[text()[contains(.,"Transformatorweg, Zeeburg")]]');

        /*
         * Should match Geen diensten
         */
        $this->checkIntakeOnDiensten(4, '//td[text()[contains(.,"Geen diensten")]]');

        /*
         * Should match all locaties, want legaal.
         */
        $this->checkIntakeOnDiensten(5, '//a[text()[contains(.,"Blaka Watra, ")]]');
    }

    private function editToegang($id)
    {
        $crawler = $this->client->request('GET', "/inloop/intakes/$id/editToegang");
        $this->assertStatusCode(200, $this->client);
        $form = $crawler->selectButton('toegang[submit]')->form();
        $crawler = $this->client->submit($form);
        $this->assertStatusCode(302, $this->client); // redirect to view.
    }

    private function checkIntakeOnDiensten($id, $xPathExpression)
    {
        $crawler = $this->client->request('GET', "/inloop/intakes/$id/view");
//        file_put_contents("/tmp/debug.html", $crawler->html());
        $this->assertStatusCode(200, $this->client);
        $node = $crawler->filterXPath($xPathExpression);
        $this->assertTrue(1 == $node->count());
    }
}
