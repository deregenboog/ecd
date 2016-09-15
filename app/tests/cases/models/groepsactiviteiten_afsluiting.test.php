<?php
/* GroepsactiviteitenAfsluiting Test cases generated on: 2015-11-22 08:11:01 : 1448175901*/
App::import('Model', 'GroepsactiviteitenAfsluiting');

class GroepsactiviteitenAfsluitingTestCase extends CakeTestCase {
	var $fixtures = array('app.groepsactiviteiten_afsluiting');

	function startTest() {
		$this->GroepsactiviteitenAfsluiting =& ClassRegistry::init('GroepsactiviteitenAfsluiting');
	}

	function endTest() {
		unset($this->GroepsactiviteitenAfsluiting);
		ClassRegistry::flush();
	}

}
?>