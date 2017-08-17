<?php

/* AwbzHoofdaanemers Test cases generated on: 2011-03-25 12:03:23 : 1301051303*/
App::import('Controller', 'AwbzHoofdaanemers');

class TestAwbzHoofdaanemersController extends AwbzHoofdaanemersController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class AwbzHoofdaanemersControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.awbz_hoofdaanemer', 'app.klant', 'app.geslacht', 'app.land', 'app.nationaliteit', 'app.medewerker', 'app.ldap_user', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslaving', 'app.intakes_verslavingen', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_reden', 'app.inkomen', 'app.inkomens_intake', 'app.instantie', 'app.instanties_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijze', 'app.primaireproblematieksgebruikswijze', 'app.intakes_primaireproblematieksgebruikswijze', 'app.verslag', 'app.inventarisaties_verslagen', 'app.inventarisatie', 'app.doorverwijzer', 'app.notitie', 'app.opmerking', 'app.categorie', 'app.hoofdaannemer'];

    public function startTest()
    {
        $this->AwbzHoofdaanemers = new TestAwbzHoofdaanemersController();
        $this->AwbzHoofdaanemers->constructClasses();
    }

    public function endTest()
    {
        unset($this->AwbzHoofdaanemers);
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
