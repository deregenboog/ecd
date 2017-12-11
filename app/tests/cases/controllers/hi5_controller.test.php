<?php

/* Hi5 Test cases generated on: 2011-04-15 15:04:09 : 1302872589*/
App::import('Controller', 'Hi5');

class TestHi5Controller extends Hi5Controller
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class Hi5ControllerTestCase extends CakeTestCase
{
    public $fixtures = [
            'app.klant',
            'app.geslacht',
            'app.land',
            'app.nationaliteit',
            'app.medewerker',
            'app.ldap_user',
            'app.intake',
            'app.verblijfstatus',
            'app.legitimatie',
            'app.verslaving',
            'app.awbz_intake',
            'app.verslavingsfrequentie',
            'app.verslavingsperiode',
            'app.woonsituatie',
            'app.locatie',
            'app.registratie',
            'app.schorsing',
            'app.reden',
            'app.schorsingen_reden',
            'app.inkomen',
            'app.inkomens_intake',
            'app.inkomens_awbz_intake',
            'app.instantie',
            'app.instanties_intake',
            'app.instanties_awbz_intake',
            'app.verslavingsgebruikswijze',
            'app.intakes_verslavingen',
            'app.intakes_verslavingsgebruikswijze',
            'app.awbz_intakes_verslavingsgebruikswijze',
            'app.primaireproblematieksgebruikswijze',
            'app.intakes_primaireproblematieksgebruikswijze',
            'app.awbz_intakes_primaireproblematieksgebruikswijze',
            'app.awbz_intake_verslaving',
            'app.verslag',
            'app.inventarisaties_verslag',
            'app.inventarisatie',
            'app.doorverwijzer',
            'app.notitie',
            'app.traject',
            'app.awbz_hoofdaannemer',
            'app.hoofdaannemer',
            'app.awbz_indicatie',
            'app.opmerking',
            'app.categorie',
            'app.hi5_intake',
            'app.bedrijfitem',
            'app.bedrijfsector',
            'app.hi5_intakes_verslavingsgebruikswijzen',
            'app.hi5_intakes_primaireproblematieksgebruikswijzen',
            'app.hi5_intakes_verslavingen',
            'app.hi5_intakes_inkomen',
            'app.hi5_intakes_instanty',
            'app.hi5_answer',
            'app.hi5_question',
            'app.hi5_answer_type',
            'app.hi5_intakes_answer',
    ];

    public function startTest()
    {
        $this->Hi5 = new TestHi5Controller();
        $this->Hi5->constructClasses();
    }

    public function endTest()
    {
        unset($this->Hi5);
        ClassRegistry::flush();
    }

    public function testAddEvaluatie()
    {
        Configure::write('ACL.disabled', true);
        //Test Klant id
        $klantId = 106;
        $return = $this->testAction('hi5/add_evaluatie/debug', [
                106,
        ]);
        debug($return);
    }
}
