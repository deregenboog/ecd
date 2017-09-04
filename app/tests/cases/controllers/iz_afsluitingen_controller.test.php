<?php

/* IzAfsluitingen Test cases generated on: 2014-08-11 13:08:10 : 1407757270*/
App::import('Controller', 'IzAfsluitingen');

class TestIzAfsluitingenController extends IzAfsluitingenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class IzAfsluitingenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_afsluiting'];

    public function startTest()
    {
        $this->IzAfsluitingen = new TestIzAfsluitingenController();
        $this->IzAfsluitingen->constructClasses();
    }

    public function endTest()
    {
        unset($this->IzAfsluitingen);
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
