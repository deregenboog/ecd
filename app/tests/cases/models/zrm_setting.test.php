<?php
/* ZrmSetting Test cases generated on: 2013-11-26 17:11:39 : 1385484879*/
App::import('Model', 'ZrmSetting');

class ZrmSettingTestCase extends CakeTestCase {
	var $fixtures = array('app.zrm_setting');

	function startTest() {
		$this->ZrmSetting =& ClassRegistry::init('ZrmSetting');
	}

	function endTest() {
		unset($this->ZrmSetting);
		ClassRegistry::flush();
	}

}
?>