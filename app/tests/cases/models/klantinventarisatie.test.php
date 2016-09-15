<?php
/* Klantinventarisatie Test cases generated on: 2014-05-08 17:05:42 : 1399563282*/
App::import('Model', 'Klantinventarisatie');

class KlantinventarisatieTestCase extends CakeTestCase {
	var $fixtures = array('app.klantinventarisatie');

	function startTest() {
		$this->Klantinventarisatie =& ClassRegistry::init('Klantinventarisatie');
	}

	function endTest() {
		unset($this->Klantinventarisatie);
		ClassRegistry::flush();
	}

}
?>