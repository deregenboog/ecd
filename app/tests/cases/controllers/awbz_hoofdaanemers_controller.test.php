<?php
/* AwbzHoofdaanemers Test cases generated on: 2011-03-25 12:03:23 : 1301051303*/
App::import('Controller', 'AwbzHoofdaanemers');

class TestAwbzHoofdaanemersController extends AwbzHoofdaanemersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AwbzHoofdaanemersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.awbz_hoofdaanemer', 'app.klant', 'app.geslacht', 'app.land', 'app.nationaliteit', 'app.medewerker', 'app.ldap_user', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslaving', 'app.intakes_verslavingen', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_reden', 'app.inkomen', 'app.inkomens_intake', 'app.instantie', 'app.instanties_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijze', 'app.primaireproblematieksgebruikswijze', 'app.intakes_primaireproblematieksgebruikswijze', 'app.verslag', 'app.inventarisaties_verslagen', 'app.inventarisatie', 'app.doorverwijzer', 'app.notitie', 'app.opmerking', 'app.categorie', 'app.hoofdaannemer');

	function startTest() {
		$this->AwbzHoofdaanemers =& new TestAwbzHoofdaanemersController();
		$this->AwbzHoofdaanemers->constructClasses();
	}

	function endTest() {
		unset($this->AwbzHoofdaanemers);
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