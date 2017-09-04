<?php

/* IzOntstaanContacten Test cases generated on: 2014-12-12 15:12:27 : 1418393007*/
App::import('Controller', 'IzOntstaanContacten');

class TestIzOntstaanContactenController extends IzOntstaanContactenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class IzOntstaanContactenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_ontstaan_contact'];

    public function startTest()
    {
        $this->IzOntstaanContacten = new TestIzOntstaanContactenController();
        $this->IzOntstaanContacten->constructClasses();
    }

    public function endTest()
    {
        unset($this->IzOntstaanContacten);
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
