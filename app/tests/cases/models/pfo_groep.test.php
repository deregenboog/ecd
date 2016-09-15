<?php
/* PfoGroep Test cases generated on: 2013-06-09 19:06:11 : 1370799491*/
App::import('Model', 'PfoGroep');

class PfoGroepTestCase extends CakeTestCase {
	var $fixtures = array('app.pfo_groep');

	function startTest() {
		$this->PfoGroep =& ClassRegistry::init('PfoGroep');
	}

	function endTest() {
		unset($this->PfoGroep);
		ClassRegistry::flush();
	}

}
?>