<?php
/* PfoGroepen Test cases generated on: 2013-06-09 19:06:27 : 1370799507*/
App::import('Controller', 'PfoGroepen');

class TestPfoGroepenController extends PfoGroepenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PfoGroepenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.pfo_groep');

	function startTest() {
		$this->PfoGroepen =& new TestPfoGroepenController();
		$this->PfoGroepen->constructClasses();
	}

	function endTest() {
		unset($this->PfoGroepen);
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