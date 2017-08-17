<?php

/* IzOntstaanContact Test cases generated on: 2014-12-12 15:12:17 : 1418392817*/
App::import('Model', 'IzOntstaanContact');

class IzOntstaanContactTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_ontstaan_contact'];

    public function startTest()
    {
        $this->IzOntstaanContact = &ClassRegistry::init('IzOntstaanContact');
    }

    public function endTest()
    {
        unset($this->IzOntstaanContact);
        ClassRegistry::flush();
    }
}
