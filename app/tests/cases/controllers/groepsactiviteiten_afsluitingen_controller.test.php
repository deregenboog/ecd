<?php
/* GroepsactiviteitenAfsluitingen Test cases generated on: 2015-11-22 08:11:28 : 1448175928*/
App::import('Controller', 'GroepsactiviteitenAfsluitingen');

class TestGroepsactiviteitenAfsluitingenController extends GroepsactiviteitenAfsluitingenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class GroepsactiviteitenAfsluitingenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.groepsactiviteiten_afsluiting');

	function startTest() {
		$this->GroepsactiviteitenAfsluitingen =& new TestGroepsactiviteitenAfsluitingenController();
		$this->GroepsactiviteitenAfsluitingen->constructClasses();
	}

	function endTest() {
		unset($this->GroepsactiviteitenAfsluitingen);
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