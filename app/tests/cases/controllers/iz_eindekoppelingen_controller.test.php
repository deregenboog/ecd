<?php
/* IzEindekoppelingen Test cases generated on: 2014-08-11 13:08:49 : 1407755629*/
App::import('Controller', 'IzEindekoppelingen');

class TestIzEindekoppelingenController extends IzEindekoppelingenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class IzEindekoppelingenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_eindekoppeling');

	function startTest() {
		$this->IzEindekoppelingen =& new TestIzEindekoppelingenController();
		$this->IzEindekoppelingen->constructClasses();
	}

	function endTest() {
		unset($this->IzEindekoppelingen);
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