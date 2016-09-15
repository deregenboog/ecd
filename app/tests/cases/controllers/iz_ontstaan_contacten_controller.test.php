<?php
/* IzOntstaanContacten Test cases generated on: 2014-12-12 15:12:27 : 1418393007*/
App::import('Controller', 'IzOntstaanContacten');

class TestIzOntstaanContactenController extends IzOntstaanContactenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class IzOntstaanContactenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_ontstaan_contact');

	function startTest() {
		$this->IzOntstaanContacten =& new TestIzOntstaanContactenController();
		$this->IzOntstaanContacten->constructClasses();
	}

	function endTest() {
		unset($this->IzOntstaanContacten);
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