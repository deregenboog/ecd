<?php
/* ZrmSettings Test cases generated on: 2013-11-26 17:11:56 : 1385484896*/
App::import('Controller', 'ZrmSettings');

class TestZrmSettingsController extends ZrmSettingsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ZrmSettingsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.zrm_setting');

	function startTest() {
		$this->ZrmSettings =& new TestZrmSettingsController();
		$this->ZrmSettings->constructClasses();
	}

	function endTest() {
		unset($this->ZrmSettings);
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