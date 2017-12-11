<?php

/* IzIntervisiegroep Test cases generated on: 2014-08-05 16:08:58 : 1407248458*/
App::import('Model', 'IzIntervisiegroep');

class IzIntervisiegroepTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_intervisiegroep'];

    public function startTest()
    {
        $this->IzIntervisiegroep = &ClassRegistry::init('IzIntervisiegroep');
    }

    public function endTest()
    {
        unset($this->IzIntervisiegroep);
        ClassRegistry::flush();
    }
}
