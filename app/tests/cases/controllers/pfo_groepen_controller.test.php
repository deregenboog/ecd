<?php

/* PfoGroepen Test cases generated on: 2013-06-09 19:06:27 : 1370799507*/
App::import('Controller', 'PfoGroepen');

class TestPfoGroepenController extends PfoGroepenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class PfoGroepenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.pfo_groep'];

    public function startTest()
    {
        $this->PfoGroepen = new TestPfoGroepenController();
        $this->PfoGroepen->constructClasses();
    }

    public function endTest()
    {
        unset($this->PfoGroepen);
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
