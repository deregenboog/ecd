<?php

/* ZrmReports Test cases generated on: 2013-11-26 11:11:54 : 1385462634*/
App::import('Controller', 'ZrmReports');

class TestZrmReportsController extends ZrmReportsController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class ZrmReportsControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.zrm_report'];

    public function startTest()
    {
        $this->ZrmReports = new TestZrmReportsController();
        $this->ZrmReports->constructClasses();
    }

    public function endTest()
    {
        unset($this->ZrmReports);
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
