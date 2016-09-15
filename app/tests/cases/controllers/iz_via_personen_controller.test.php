<?php
/* IzViaPersonen Test cases generated on: 2014-12-12 15:12:41 : 1418393621*/
App::import('Controller', 'IzViaPersonen');

class TestIzViaPersonenController extends IzViaPersonenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class IzViaPersonenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_via_persoon');

	function startTest() {
		$this->IzViaPersonen =& new TestIzViaPersonenController();
		$this->IzViaPersonen->constructClasses();
	}

	function endTest() {
		unset($this->IzViaPersonen);
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