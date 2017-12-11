<?php

/* Postcodegebied Test cases generated on: 2015-12-05 13:12:07 : 1449319327*/
App::import('Model', 'Postcodegebied');

class PostcodegebiedTestCase extends CakeTestCase
{
    public $fixtures = ['app.postcodegebied'];

    public function startTest()
    {
        $this->Postcodegebied = &ClassRegistry::init('Postcodegebied');
    }

    public function endTest()
    {
        unset($this->Postcodegebied);
        ClassRegistry::flush();
    }
}
