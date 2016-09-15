<?php
/* ZrmReports Test cases generated on: 2013-11-26 11:11:54 : 1385462634*/
App::import('Controller', 'ZrmReports');

class TestZrmReportsController extends ZrmReportsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ZrmReportsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.zrm_report');

	function startTest() {
		$this->ZrmReports =& new TestZrmReportsController();
		$this->ZrmReports->constructClasses();
	}

	function endTest() {
		unset($this->ZrmReports);
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