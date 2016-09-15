<?php
/* GroepsactiviteitenGroepen Test cases generated on: 2014-05-03 12:05:36 : 1399111476*/
App::import('Controller', 'GroepsactiviteitenGroepen');

class TestGroepsactiviteitenGroepenController extends GroepsactiviteitenGroepenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class GroepsactiviteitenGroepenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.groepsactiviteiten_groep', 'app.groepsactiviteit');

	function startTest() {
		$this->GroepsactiviteitenGroepen =& new TestGroepsactiviteitenGroepenController();
		$this->GroepsactiviteitenGroepen->constructClasses();
	}

	function endTest() {
		unset($this->GroepsactiviteitenGroepen);
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