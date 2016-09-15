<?php
/* Stadsdeel Test cases generated on: 2014-05-05 16:05:28 : 1399300888*/
App::import('Model', 'Stadsdeel');

class StadsdeelTestCase extends CakeTestCase {
	var $fixtures = array('app.stadsdeel');

	function startTest() {
		$this->Stadsdeel =& ClassRegistry::init('Stadsdeel');
	}

	function endTest() {
		unset($this->Stadsdeel);
		ClassRegistry::flush();
	}

}
?>