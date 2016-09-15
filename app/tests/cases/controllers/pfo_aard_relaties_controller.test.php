<?php
/* PfoAardRelaties Test cases generated on: 2013-06-09 19:06:17 : 1370799677*/
App::import('Controller', 'PfoAardRelaties');

class TestPfoAardRelatiesController extends PfoAardRelatiesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PfoAardRelatiesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.pfo_aard_relatie');

	function startTest() {
		$this->PfoAardRelaties =& new TestPfoAardRelatiesController();
		$this->PfoAardRelaties->constructClasses();
	}

	function endTest() {
		unset($this->PfoAardRelaties);
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