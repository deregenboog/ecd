<?php

/* GroepsactiviteitenAfsluitingen Test cases generated on: 2015-11-22 08:11:28 : 1448175928*/
App::import('Controller', 'GroepsactiviteitenAfsluitingen');

class TestGroepsactiviteitenAfsluitingenController extends GroepsactiviteitenAfsluitingenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class GroepsactiviteitenAfsluitingenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.groepsactiviteiten_afsluiting'];

    public function startTest()
    {
        $this->GroepsactiviteitenAfsluitingen = new TestGroepsactiviteitenAfsluitingenController();
        $this->GroepsactiviteitenAfsluitingen->constructClasses();
    }

    public function endTest()
    {
        unset($this->GroepsactiviteitenAfsluitingen);
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
