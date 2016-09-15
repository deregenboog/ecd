<?php
/* BtoVerslag Test cases generated on: 2013-10-07 17:10:10 : 1381161130*/
App::import('Model', 'BtoVerslag');

class BtoVerslagTestCase extends CakeTestCase {
	var $fixtures = array('app.bot_verslag', 'app.medewerker', 'app.ldap_user');

	function startTest() {
		$this->BtoVerslag =& ClassRegistry::init('BtoVerslag');
	}

	function endTest() {
		unset($this->BtoVerslag);
		ClassRegistry::flush();
	}

}
?>
