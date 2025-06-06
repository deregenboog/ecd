<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

trait DisableIndexActionTrait
{
    /**
     * Het idee achter deze trait en het overriden van de indexAction komt voor uit de tests/AppBundle/Controller/ControllerTest
     * Deze gaat namelijk alle beschikbare index routes af om te kijken of die een tabel teruggeven.
     * Dit is handig. Maar, niet alle controllers hoeven een index route te hebben.
     * Daar is deze trait voor.
     *
     * Het was mooier geweest om die controllers te laten overerven van een nieuwe Abstract controller:
     * NoIndexAbstractController, waarin dit is dan geregeld wordt. Dat is veel transparanter.
     *
     * @todo Alle controllers die deze trait gbruiken laten overerven van (nieuw te maken) NoIndexAbstractController.
     * Evt kan die NoIndexAbstractController natuurlijk deze trait gebruiken ;)
     *
     * @param Request $request
     * @return void
     */
    public function indexAction(Request $request)
    {
    }
}
