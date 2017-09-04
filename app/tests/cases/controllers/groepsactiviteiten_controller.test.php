<?php

/* Groepsactiviteiten Test cases generated on: 2014-05-03 12:05:19 : 1399111279*/
App::import('Controller', 'Groepsactiviteiten');

class TestGroepsactiviteitenController extends GroepsactiviteitenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class GroepsactiviteitenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.groepsactiviteit', 'app.groepsactiviteiten_groep'];

    public function startTest()
    {
        $this->Groepsactiviteiten = new TestGroepsactiviteitenController();
        $this->Groepsactiviteiten->constructClasses();
    }

    public function endTest()
    {
        unset($this->Groepsactiviteiten);
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
