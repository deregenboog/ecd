<?php

/* Opmerkingen Test cases generated on: 2010-10-05 15:10:36 : 1286286996*/
App::import('Controller', 'Opmerkingen');

class TestOpmerkingenController extends OpmerkingenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class OpmerkingenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.opmerking', 'app.intakes_primaireproblematieksgebruikswijze', 'app.klant', 'app.geslacht', 'app.land', 'app.nationaliteit', 'app.medewerker', 'app.ldap_user', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_redenen', 'app.inkomen', 'app.inkomens_intake', 'app.instantie', 'app.instanties_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijzen', 'app.verslaving', 'app.intakes_verslavingen', 'app.notitie', 'app.klantinventarisatie', 'app.inventarisatie', 'app.doorverwijzer', 'app.categorie'];

    public function startTest()
    {
        $this->Opmerkingen = new TestOpmerkingenController();
        $this->Opmerkingen->constructClasses();
    }

    public function endTest()
    {
        unset($this->Opmerkingen);
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
