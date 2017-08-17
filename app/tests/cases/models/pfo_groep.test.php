<?php

/* PfoGroep Test cases generated on: 2013-06-09 19:06:11 : 1370799491*/
App::import('Model', 'PfoGroep');

class PfoGroepTestCase extends CakeTestCase
{
    public $fixtures = ['app.pfo_groep'];

    public function startTest()
    {
        $this->PfoGroep = &ClassRegistry::init('PfoGroep');
    }

    public function endTest()
    {
        unset($this->PfoGroep);
        ClassRegistry::flush();
    }
}
