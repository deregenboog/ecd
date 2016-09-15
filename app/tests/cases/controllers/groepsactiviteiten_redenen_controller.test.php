<?php
/* GroepsactiviteitenRedenen Test cases generated on: 2014-05-03 12:05:23 : 1399111823*/
App::import('Controller', 'GroepsactiviteitenRedenen');

class TestGroepsactiviteitenRedenenController extends GroepsactiviteitenRedenenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class GroepsactiviteitenRedenenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.groepsactiviteiten_reden');

	function startTest() {
		$this->GroepsactiviteitenRedenen =& new TestGroepsactiviteitenRedenenController();
		$this->GroepsactiviteitenRedenen->constructClasses();
	}

	function endTest() {
		unset($this->GroepsactiviteitenRedenen);
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