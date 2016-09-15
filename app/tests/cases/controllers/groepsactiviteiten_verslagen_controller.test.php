<?php
/* GroepsactiviteitenVerslags Test cases generated on: 2014-05-06 14:05:24 : 1399378404*/
App::import('Controller', 'GroepsactiviteitenVerslagen');

class TestGroepsactiviteitenVerslagenController extends GroepsactiviteitenVerslagenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class GroepsactiviteitenVerslagenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.groepsactiviteiten_verslag');

	function startTest() {
		$this->GroepsactiviteitenVerslagen =& new TestGroepsactiviteitenVerslagenController();
		$this->GroepsactiviteitenVerslagen->constructClasses();
	}

	function endTest() {
		unset($this->GroepsactiviteitenVerslagen);
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