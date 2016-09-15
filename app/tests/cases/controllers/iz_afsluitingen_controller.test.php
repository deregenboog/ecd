<?php
/* IzAfsluitingen Test cases generated on: 2014-08-11 13:08:10 : 1407757270*/
App::import('Controller', 'IzAfsluitingen');

class TestIzAfsluitingenController extends IzAfsluitingenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class IzAfsluitingenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_afsluiting');

	function startTest() {
		$this->IzAfsluitingen =& new TestIzAfsluitingenController();
		$this->IzAfsluitingen->constructClasses();
	}

	function endTest() {
		unset($this->IzAfsluitingen);
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