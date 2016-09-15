<?php
/* Postcodegebied Test cases generated on: 2015-12-05 13:12:07 : 1449319327*/
App::import('Model', 'Postcodegebied');

class PostcodegebiedTestCase extends CakeTestCase {
	var $fixtures = array('app.postcodegebied');

	function startTest() {
		$this->Postcodegebied =& ClassRegistry::init('Postcodegebied');
	}

	function endTest() {
		unset($this->Postcodegebied);
		ClassRegistry::flush();
	}

}
?>