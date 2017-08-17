<?php

/* Groepsactiviteit Test cases generated on: 2014-05-03 15:05:33 : 1399122873*/
App::import('Model', 'Groepsactiviteit');

class GroepsactiviteitTestCase extends CakeTestCase
{
    public $fixtures = ['app.groepsactiviteit', 'app.groepsactiviteiten_groep'];

    public function startTest()
    {
        $this->Groepsactiviteit = &ClassRegistry::init('Groepsactiviteit');
    }

    public function endTest()
    {
        unset($this->Groepsactiviteit);
        ClassRegistry::flush();
    }
}
