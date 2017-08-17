<?php

/* Hi5Intakes Test cases generated on: 2011-04-14 10:04:20 : 1302769460*/
App::import('Controller', 'Hi5Intakes');

class TestHi5IntakesController extends Hi5IntakesController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class Hi5IntakesControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.hi5_intake', 'app.klant', 'app.geslacht', 'app.land', 'app.nationaliteit', 'app.medewerker', 'app.ldap_user', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslaving', 'app.intake_verslaving', 'app.awbz_intake', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_reden', 'app.inkomen', 'app.inkomens_intake', 'app.inkomens_awbz_intake', 'app.instantie', 'app.instanties_intake', 'app.instanties_awbz_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijze', 'app.awbz_intakes_verslavingsgebruikswijze', 'app.primaireproblematieksgebruikswijze', 'app.intakes_primaireproblematieksgebruikswijze', 'app.awbz_intakes_primaireproblematieksgebruikswijze', 'app.awbz_intake_verslaving', 'app.verslag', 'app.inventarisaties_verslagen', 'app.inventarisatie', 'app.doorverwijzer', 'app.notitie', 'app.traject', 'app.awbz_hoofdaannemer', 'app.hoofdaannemer', 'app.awbz_indicatie', 'app.opmerking', 'app.categorie', 'app.bedrijfitem', 'app.bedrijfsector', 'app.hi5_intakes_verslavingsgebruikswijzen', 'app.hi5_intakes_primaireproblematieksgebruikswijzen', 'app.hi5_intakes_verslavingen', 'app.hi5_intakes_inkomen', 'app.hi5_intakes_instanty', 'app.hi5_answer', 'app.hi5_question', 'app.hi5_answer_type', 'app.hi5_intakes_answer'];

    public function startTest()
    {
        $this->Hi5Intakes = new TestHi5IntakesController();
        $this->Hi5Intakes->constructClasses();
    }

    public function endTest()
    {
        unset($this->Hi5Intakes);
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
