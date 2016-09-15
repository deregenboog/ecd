<?php
/* Izeindekoppelingen Test cases generated on: 2014-08-11 13:08:00 : 1407755220*/
App::import('Controller', 'Izeindekoppelingen');

class TestIzeindekoppelingenController extends IzeindekoppelingenController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class IzeindekoppelingenControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_eindekoppeling');

	function startTest() {
		$this->Izeindekoppelingen =& new TestIzeindekoppelingenController();
		$this->Izeindekoppelingen->constructClasses();
	}

	function endTest() {
		unset($this->Izeindekoppelingen);
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