<?php
/* IzDeelnemer Test cases generated on: 2014-08-04 10:08:19 : 1407139879*/
App::import('Model', 'IzDeelnemer');

class IzDeelnemerTestCase extends CakeTestCase
{
    public $fixtures = array('app.iz_deelnemer');

    public function startTest()
    {
        $this->IzDeelnemer =& ClassRegistry::init('IzDeelnemer');
    }

    public function endTest()
    {
        unset($this->IzDeelnemer);
        ClassRegistry::flush();
    }
}
