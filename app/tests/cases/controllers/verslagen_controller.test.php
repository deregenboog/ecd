<?php
/* Verslagen Test cases generated on: 2010-11-03 15:11:36 : 1288794636*/
App::import('Controller', 'Verslagen');

class TestVerslagenController extends VerslagenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class VerslagenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.verslag', 'app.klant', 'app.geslacht', 'app.land', 'app.nationaliteit', 'app.medewerker', 'app.ldap_user', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslaving', 'app.intakes_verslavingen', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_redenen', 'app.inkomen', 'app.inkomens_intake', 'app.instantie', 'app.instanties_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijzen', 'app.primaireproblematieksgebruikswijze', 'app.intakes_primaireproblematieksgebruikswijzen', 'app.notitie', 'app.opmerking', 'app.categorie', 'app.inventarisaties_verslagen', 'app.inventarisatie',
        'app.intakes_primaireproblematieksgebruikswijze', 'app.doorverwijzer');

	function startTest() {
		$this->Verslagen =& new TestVerslagenController();
		$this->Verslagen->constructClasses();
	}

	function endTest() {
		unset($this->Verslagen);
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