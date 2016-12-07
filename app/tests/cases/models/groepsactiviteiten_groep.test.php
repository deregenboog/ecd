<?php

/* GroepsactiviteitenGroep Test cases generated on: 2014-05-03 15:05:18 : 1399123518*/
App::import('Model', 'GroepsactiviteitenGroep');

class GroepsactiviteitenGroepTestCase extends CakeTestCase
{
    public $fixtures = array('app.groepsactiviteiten_groep', 'app.groepsactiviteit', 'app.groepsactiviteiten_vrijwilligers');

    public function startTest()
    {
        $this->GroepsactiviteitenGroep = &ClassRegistry::init('GroepsactiviteitenGroep');
    }

    public function endTest()
    {
        unset($this->GroepsactiviteitenGroep);
        ClassRegistry::flush();
    }

    public function testCompareDate()
    {
    }
}
