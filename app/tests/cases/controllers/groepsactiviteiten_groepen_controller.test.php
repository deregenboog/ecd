<?php

/* GroepsactiviteitenGroepen Test cases generated on: 2014-05-03 12:05:36 : 1399111476*/
App::import('Controller', 'GroepsactiviteitenGroepen');

class TestGroepsactiviteitenGroepenController extends GroepsactiviteitenGroepenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class GroepsactiviteitenGroepenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.groepsactiviteiten_groep', 'app.groepsactiviteit'];

    public function startTest()
    {
        $this->GroepsactiviteitenGroepen = new TestGroepsactiviteitenGroepenController();
        $this->GroepsactiviteitenGroepen->constructClasses();
    }

    public function endTest()
    {
        unset($this->GroepsactiviteitenGroepen);
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
