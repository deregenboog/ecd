<?php
/* IzViaPersoon Test cases generated on: 2014-12-12 15:12:22 : 1418393602*/
App::import('Model', 'IzViaPersoon');

class IzViaPersoonTestCase extends CakeTestCase {
	var $fixtures = array('app.iz_via_persoon');

	function startTest() {
		$this->IzViaPersoon =& ClassRegistry::init('IzViaPersoon');
	}

	function endTest() {
		unset($this->IzViaPersoon);
		ClassRegistry::flush();
	}

}
?>