<?php

/* IzViaPersonen Test cases generated on: 2014-12-12 15:12:41 : 1418393621*/
App::import('Controller', 'IzViaPersonen');

class TestIzViaPersonenController extends IzViaPersonenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class IzViaPersonenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_via_persoon'];

    public function startTest()
    {
        $this->IzViaPersonen = new TestIzViaPersonenController();
        $this->IzViaPersonen->constructClasses();
    }

    public function endTest()
    {
        unset($this->IzViaPersonen);
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
