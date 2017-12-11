<?php

/* GroepsactiviteitenVerslags Test cases generated on: 2014-05-06 14:05:24 : 1399378404*/
App::import('Controller', 'GroepsactiviteitenVerslagen');

class TestGroepsactiviteitenVerslagenController extends GroepsactiviteitenVerslagenController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class GroepsactiviteitenVerslagenControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.groepsactiviteiten_verslag'];

    public function startTest()
    {
        $this->GroepsactiviteitenVerslagen = new TestGroepsactiviteitenVerslagenController();
        $this->GroepsactiviteitenVerslagen->constructClasses();
    }

    public function endTest()
    {
        unset($this->GroepsactiviteitenVerslagen);
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
