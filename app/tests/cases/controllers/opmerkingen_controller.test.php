<?php
/* Opmerkingen Test cases generated on: 2010-10-05 15:10:36 : 1286286996*/
App::import('Controller', 'Opmerkingen');

class TestOpmerkingenController extends OpmerkingenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class OpmerkingenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.opmerking', 'app.intakes_primaireproblematieksgebruikswijze', 'app.klant', 'app.geslacht', 'app.land', 'app.nationaliteit', 'app.medewerker', 'app.ldap_user', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_redenen', 'app.inkomen', 'app.inkomens_intake', 'app.instantie', 'app.instanties_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijzen', 'app.verslaving', 'app.intakes_verslavingen', 'app.notitie', 'app.klantinventarisatie', 'app.inventarisatie', 'app.doorverwijzer', 'app.categorie');

	function startTest() {
		$this->Opmerkingen =& new TestOpmerkingenController();
		$this->Opmerkingen->constructClasses();
	}

	function endTest() {
		unset($this->Opmerkingen);
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