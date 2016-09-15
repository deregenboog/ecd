<?php
/* IzIntervisiegroepen Test cases generated on: 2014-08-05 16:08:18 : 1407248478*/
App::import('Controller', 'IzIntervisiegroepen');

class TestIzIntervisiegroepenController extends IzIntervisiegroepenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class IzIntervisiegroepenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_intervisiegroep');

	function startTest() {
		$this->IzIntervisiegroepen =& new TestIzIntervisiegroepenController();
		$this->IzIntervisiegroepen->constructClasses();
	}

	function endTest() {
		unset($this->IzIntervisiegroepen);
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