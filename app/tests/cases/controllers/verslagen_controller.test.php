<?php

/* Verslagen Test cases generated on: 2010-11-03 15:11:36 : 1288794636*/
App::import('Controller', 'Verslagen');

class TestVerslagenController extends VerslagenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class VerslagenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.verslag', 'app.klant', 'app.geslacht', 'app.land', 'app.nationaliteit', 'app.medewerker', 'app.ldap_user', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslaving', 'app.intakes_verslavingen', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_redenen', 'app.inkomen', 'app.inkomens_intake', 'app.instantie', 'app.instanties_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijzen', 'app.primaireproblematieksgebruikswijze', 'app.intakes_primaireproblematieksgebruikswijzen', 'app.notitie', 'app.opmerking', 'app.categorie', 'app.inventarisaties_verslagen', 'app.inventarisatie',
        'app.intakes_primaireproblematieksgebruikswijze', 'app.doorverwijzer', ];

    public function startTest()
    {
        $this->Verslagen = new TestVerslagenController();
        $this->Verslagen->constructClasses();
    }

    public function endTest()
    {
        unset($this->Verslagen);
        ClassRegistry::flush();
    }

    public function testIndex()
    {
    }

    public function testView()
    {
    }

    public function testAdd()
    {
    }

    public function testEdit()
    {
    }

    public function testDelete()
    {
    }
}
