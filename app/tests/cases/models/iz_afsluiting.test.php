<?php
/* IzAfsluiting Test cases generated on: 2014-08-13 14:08:13 : 1407934033*/
App::import('Model', 'IzAfsluiting');

class IzAfsluitingTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_afsluiting');

	function startTest() {
		$this->IzAfsluiting =& ClassRegistry::init('IzAfsluiting');
	}

	function endTest() {
		unset($this->IzAfsluiting);
		ClassRegistry::flush();
	}

}
?>