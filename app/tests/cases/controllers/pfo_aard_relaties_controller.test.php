<?php

/* PfoAardRelaties Test cases generated on: 2013-06-09 19:06:17 : 1370799677*/
App::import('Controller', 'PfoAardRelaties');

class TestPfoAardRelatiesController extends PfoAardRelatiesController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class PfoAardRelatiesControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.pfo_aard_relatie'];

    public function startTest()
    {
        $this->PfoAardRelaties = new TestPfoAardRelatiesController();
        $this->PfoAardRelaties->constructClasses();
    }

    public function endTest()
    {
        unset($this->PfoAardRelaties);
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
