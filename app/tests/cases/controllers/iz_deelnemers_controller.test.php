<?php
/* IzDeelnemers Test cases generated on: 2014-08-04 10:08:36 : 1407139896*/
App::import('Controller', 'IzDeelnemers');

class TestIzDeelnemersController extends IzDeelnemersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class IzDeelnemersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_deelnemer');

	function startTest() {
		$this->IzDeelnemers =& new TestIzDeelnemersController();
		$this->IzDeelnemers->constructClasses();
	}

	function endTest() {
		unset($this->IzDeelnemers);
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