<?php
/* Groepsactiviteit Test cases generated on: 2014-05-03 15:05:33 : 1399122873*/
App::import('Model', 'Groepsactiviteit');

class GroepsactiviteitTestCase extends CakeTestCase {
	var $fixtures = array('app.groepsactiviteit', 'app.groepsactiviteiten_groep');

	function startTest() {
		$this->Groepsactiviteit =& ClassRegistry::init('Groepsactiviteit');
	}

	function endTest() {
		unset($this->Groepsactiviteit);
		ClassRegistry::flush();
	}

}
?>