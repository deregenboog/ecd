<?php
/* IzDeelnemer Test cases generated on: 2014-08-04 10:08:19 : 1407139879*/
App::import('Model', 'IzDeelnemer');

class IzDeelnemerTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_deelnemer');

	function startTest() {
		$this->IzDeelnemer =& ClassRegistry::init('IzDeelnemer');
	}

	function endTest() {
		unset($this->IzDeelnemer);
		ClassRegistry::flush();
	}

}
?>