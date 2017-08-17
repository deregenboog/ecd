<?php

/* IzIntervisiegroepen Test cases generated on: 2014-08-05 16:08:18 : 1407248478*/
App::import('Controller', 'IzIntervisiegroepen');

class TestIzIntervisiegroepenController extends IzIntervisiegroepenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class IzIntervisiegroepenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_intervisiegroep'];

    public function startTest()
    {
        $this->IzIntervisiegroepen = new TestIzIntervisiegroepenController();
        $this->IzIntervisiegroepen->constructClasses();
    }

    public function endTest()
    {
        unset($this->IzIntervisiegroepen);
        ClassRegistry::flush();
    }

    public function testIndex()
    {
    }

    public function testView()
    {
    }

    public function testAdd()
    {
    }

    public function testEdit()
    {
    }

    public function testDelete()
    {
    }
}
