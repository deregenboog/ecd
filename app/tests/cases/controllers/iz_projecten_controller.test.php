<?php
/* IzProjecten Test cases generated on: 2014-08-04 11:08:19 : 1407146239*/
App::import('Controller', 'IzProjecten');

class TestIzProjectenController extends IzProjectenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class IzProjectenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_project');

	function startTest() {
		$this->IzProjecten =& new TestIzProjectenController();
		$this->IzProjecten->constructClasses();
	}

	function endTest() {
		unset($this->IzProjecten);
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