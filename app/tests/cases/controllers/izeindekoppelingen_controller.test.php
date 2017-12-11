<?php

/* Izeindekoppelingen Test cases generated on: 2014-08-11 13:08:00 : 1407755220*/
App::import('Controller', 'Izeindekoppelingen');

class TestIzeindekoppelingenController extends IzeindekoppelingenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class IzeindekoppelingenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_eindekoppeling'];

    public function startTest()
    {
        $this->Izeindekoppelingen = new TestIzeindekoppelingenController();
        $this->Izeindekoppelingen->constructClasses();
    }

    public function endTest()
    {
        unset($this->Izeindekoppelingen);
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
