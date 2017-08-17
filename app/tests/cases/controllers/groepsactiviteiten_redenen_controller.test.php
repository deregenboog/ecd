<?php

/* GroepsactiviteitenRedenen Test cases generated on: 2014-05-03 12:05:23 : 1399111823*/
App::import('Controller', 'GroepsactiviteitenRedenen');

class TestGroepsactiviteitenRedenenController extends GroepsactiviteitenRedenenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class GroepsactiviteitenRedenenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.groepsactiviteiten_reden'];

    public function startTest()
    {
        $this->GroepsactiviteitenRedenen = new TestGroepsactiviteitenRedenenController();
        $this->GroepsactiviteitenRedenen->constructClasses();
    }

    public function endTest()
    {
        unset($this->GroepsactiviteitenRedenen);
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
