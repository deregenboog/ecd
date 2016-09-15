<?php
/* IzProject Test cases generated on: 2014-08-11 16:08:38 : 1407767258*/
App::import('Model', 'IzProject');

class IzProjectTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_project');

	function startTest() {
		$this->IzProject =& ClassRegistry::init('IzProject');
	}

	function endTest() {
		unset($this->IzProject);
		ClassRegistry::flush();
	}

}
?>