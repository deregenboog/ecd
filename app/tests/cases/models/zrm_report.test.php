<?php
/* ZrmReport Test cases generated on: 2013-11-26 11:11:27 : 1385462607*/
App::import('Model', 'ZrmReport');

class ZrmReportTestCase extends CakeTestCase {
	var $fixtures = array('app.zrm_report');

	function startTest() {
		$this->ZrmReport =& ClassRegistry::init('ZrmReport');
	}

	function endTest() {
		unset($this->ZrmReport);
		ClassRegistry::flush();
	}

}
?>