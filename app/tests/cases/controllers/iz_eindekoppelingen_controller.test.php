<?php

/* IzEindekoppelingen Test cases generated on: 2014-08-11 13:08:49 : 1407755629*/
App::import('Controller', 'IzEindekoppelingen');

class TestIzEindekoppelingenController extends IzEindekoppelingenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class IzEindekoppelingenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_eindekoppeling'];

    public function startTest()
    {
        $this->IzEindekoppelingen = new TestIzEindekoppelingenController();
        $this->IzEindekoppelingen->constructClasses();
    }

    public function endTest()
    {
        unset($this->IzEindekoppelingen);
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
