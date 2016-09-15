<?php
/* PfoClients Test cases generated on: 2013-06-04 22:06:56 : 1370377796*/
App::import('Controller', 'PfoClienten');

class TestPfoClientenController extends PfoClientenClientsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PfoClientenClientsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.pfo_client', 'app.geslacht', 'app.klant', 'app.land', 'app.nationaliteit', 'app.medewerker', 'app.ldap_user', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslaving', 'app.intake_verslaving', 'app.awbz_intake', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_reden', 'app.infobaliedoelgroep', 'app.inkomen', 'app.inkomens_intake', 'app.inkomens_awbz_intake', 'app.instantie', 'app.instanties_intake', 'app.instanties_awbz_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijze', 'app.awbz_intakes_verslavingsgebruikswijze', 'app.primaireproblematieksgebruikswijze', 'app.intakes_primaireproblematieksgebruikswijze', 'app.awbz_intakes_primaireproblematieksgebruikswijze', 'app.awbz_intake_verslaving', 'app.verslag', 'app.contactsoort', 'app.inventarisaties_verslagen', 'app.inventarisatie', 'app.doorverwijzer', 'app.notitie', 'app.traject', 'app.awbz_hoofdaannemer', 'app.hoofdaannemer', 'app.awbz_indicatie', 'app.opmerking', 'app.categorie', 'app.hi5_intake', 'app.bedrijfitem', 'app.bedrijfsector', 'app.hi5_intakes_verslavingsgebruikswijzen', 'app.hi5_intakes_primaireproblematieksgebruikswijzen', 'app.hi5_intakes_verslavingen', 'app.hi5_intakes_inkomen', 'app.hi5_intakes_instanty', 'app.hi5_answer', 'app.hi5_question', 'app.hi5_answer_type', 'app.hi5_intakes_answer', 'app.hi5_evaluatie', 'app.hi5_evaluatie_question', 'app.hi5_evaluatie_paragraph', 'app.hi5_evaluaties_hi5_evaluatie_question', 'app.contactjournal', 'app.verslaginfo', 'app.attachment');

	function startTest() {
		$this->PfoClienten =& new TestPfoClientenController();
		$this->PfoClienten->constructClasses();
	}

	function endTest() {
		unset($this->PfoClienten);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
?>
