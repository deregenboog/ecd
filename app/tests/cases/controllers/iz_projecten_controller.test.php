<?php

/* IzProjecten Test cases generated on: 2014-08-04 11:08:19 : 1407146239*/
App::import('Controller', 'IzProjecten');

class TestIzProjectenController extends IzProjectenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class IzProjectenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_project'];

    public function startTest()
    {
        $this->IzProjecten = new TestIzProjectenController();
        $this->IzProjecten->constructClasses();
    }

    public function endTest()
    {
        unset($this->IzProjecten);
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
