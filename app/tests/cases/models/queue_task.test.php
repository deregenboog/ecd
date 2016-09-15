<?php
/* QueueTask Test cases generated on: 2014-05-11 20:05:58 : 1399833898*/
App::import('Model', 'QueueTask');

class QueueTaskTestCase extends CakeTestCase {
	var $fixtures = array('app.queue_task');

	function startTest() {
		$this->QueueTask =& ClassRegistry::init('QueueTask');
	}

	function endTest() {
		unset($this->QueueTask);
		ClassRegistry::flush();
	}

}
?>