<?php

/* IzProject Test cases generated on: 2014-08-11 16:08:38 : 1407767258*/
App::import('Model', 'IzProject');

class IzProjectTestCase extends CakeTestCase
{
    public $fixtures = array('app.iz_project');

    public function startTest()
    {
        $this->IzProject = &ClassRegistry::init('IzProject');
    }

    public function endTest()
    {
        unset($this->IzProject);
        ClassRegistry::flush();
    }
}
