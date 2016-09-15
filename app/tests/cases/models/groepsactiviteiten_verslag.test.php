<?php
/* GroepsactiviteitenVerslag Test cases generated on: 2014-05-06 18:05:56 : 1399393016*/
App::import('Model', 'GroepsactiviteitenVerslag');

class GroepsactiviteitenVerslagTestCase extends CakeTestCase {
	var $fixtures = array('app.groepsactiviteiten_verslag', 'app.medewerker', 'app.ldap_user');

	function startTest() {
		$this->GroepsactiviteitenVerslag =& ClassRegistry::init('GroepsactiviteitenVerslag');
	}

	function endTest() {
		unset($this->GroepsactiviteitenVerslag);
		ClassRegistry::flush();
	}

}
?>