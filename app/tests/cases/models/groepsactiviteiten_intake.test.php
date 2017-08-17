<?php

/* GroepsactiviteitenIntake Test cases generated on: 2014-05-07 13:05:57 : 1399461657*/
App::import('Model', 'GroepsactiviteitenIntake');

class GroepsactiviteitenIntakeTestCase extends CakeTestCase
{
    public $fixtures = ['app.groepsactiviteiten_intake'];

    public function startTest()
    {
        $this->GroepsactiviteitenIntake = &ClassRegistry::init('GroepsactiviteitenIntake');
    }

    public function endTest()
    {
        unset($this->GroepsactiviteitenIntake);
        ClassRegistry::flush();
    }
}
