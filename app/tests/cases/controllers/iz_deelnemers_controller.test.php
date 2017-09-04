<?php

/* IzDeelnemers Test cases generated on: 2014-08-04 10:08:36 : 1407139896*/
App::import('Controller', 'IzDeelnemers');

class TestIzDeelnemersController extends IzDeelnemersController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class IzDeelnemersControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_deelnemer'];

    public function startTest()
    {
        $this->IzDeelnemers = new TestIzDeelnemersController();
        $this->IzDeelnemers->constructClasses();
    }

    public function endTest()
    {
        unset($this->IzDeelnemers);
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
