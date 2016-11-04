<?php

/* IzAfsluiting Test cases generated on: 2014-08-13 14:08:13 : 1407934033*/
App::import('Model', 'IzAfsluiting');

class IzAfsluitingTestCase extends CakeTestCase
{
    public $fixtures = array('app.iz_afsluiting');

    public function startTest()
    {
        $this->IzAfsluiting = &ClassRegistry::init('IzAfsluiting');
    }

    public function endTest()
    {
        unset($this->IzAfsluiting);
        ClassRegistry::flush();
    }
}
