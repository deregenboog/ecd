<?php
/* QueueTask Test cases generated on: 2014-05-11 20:05:58 : 1399833898*/
App::import('Model', 'QueueTask');

class QueueTaskTestCase extends CakeTestCase
{
    public $fixtures = array('app.queue_task');

    public function startTest()
    {
        $this->QueueTask =& ClassRegistry::init('QueueTask');
    }

    public function endTest()
    {
        unset($this->QueueTask);
        ClassRegistry::flush();
    }
}
