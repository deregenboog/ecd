<?php

/* PfoClientenVerslagen Test cases generated on: 2013-06-08 11:06:11 : 1370683931*/
App::import('Controller', 'PfoClientenVerslagen');

class TestPfoClientenVerslagenController extends PfoClientenVerslagenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class PfoClientenVerslagenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.pfo_clienten_verslag', 'app.pfo_client', 'app.geslacht', 'app.klant', 'app.land', 'app.nationaliteit', 'app.medewerker', 'app.ldap_user', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslaving', 'app.intake_verslaving', 'app.awbz_intake', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_reden', 'app.infobaliedoelgroep', 'app.inkomen', 'app.inkomens_intake', 'app.inkomens_awbz_intake', 'app.instantie', 'app.instanties_intake', 'app.instanties_awbz_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijze', 'app.awbz_intakes_verslavingsgebruikswijze', 'app.primaireproblematieksgebruikswijze', 'app.intakes_primaireproblematieksgebruikswijze', 'app.awbz_intakes_primaireproblematieksgebruikswijze', 'app.awbz_intake_verslaving', 'app.verslag', 'app.contactsoort', 'app.inventarisaties_verslagen', 'app.inventarisatie', 'app.doorverwijzer', 'app.notitie', 'app.traject', 'app.awbz_hoofdaannemer', 'app.hoofdaannemer', 'app.awbz_indicatie', 'app.opmerking', 'app.categorie', 'app.hi5_intake', 'app.bedrijfitem', 'app.bedrijfsector', 'app.hi5_intakes_verslavingsgebruikswijzen', 'app.hi5_intakes_primaireproblematieksgebruikswijzen', 'app.hi5_intakes_verslavingen', 'app.hi5_intakes_inkomen', 'app.hi5_intakes_instanty', 'app.hi5_answer', 'app.hi5_question', 'app.hi5_answer_type', 'app.hi5_intakes_answer', 'app.hi5_evaluatie', 'app.hi5_evaluatie_question', 'app.hi5_evaluatie_paragraph', 'app.hi5_evaluaties_hi5_evaluatie_question', 'app.contactjournal', 'app.verslaginfo', 'app.attachment', 'app.pfo_clienten_supportgroup', 'app.pfo_verslag'];

    public function startTest()
    {
        $this->PfoClientenVerslagen = new TestPfoClientenVerslagenController();
        $this->PfoClientenVerslagen->constructClasses();
    }

    public function endTest()
    {
        unset($this->PfoClientenVerslagen);
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
