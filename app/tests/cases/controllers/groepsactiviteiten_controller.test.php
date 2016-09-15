<?php
/* Groepsactiviteiten Test cases generated on: 2014-05-03 12:05:19 : 1399111279*/
App::import('Controller', 'Groepsactiviteiten');

class TestGroepsactiviteitenController extends GroepsactiviteitenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class GroepsactiviteitenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.groepsactiviteit', 'app.groepsactiviteiten_groep');

	function startTest() {
		$this->Groepsactiviteiten =& new TestGroepsactiviteitenController();
		$this->Groepsactiviteiten->constructClasses();
	}

	function endTest() {
		unset($this->Groepsactiviteiten);
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