<?php

/* ZrmSettings Test cases generated on: 2013-11-26 17:11:56 : 1385484896*/
App::import('Controller', 'ZrmSettings');

class TestZrmSettingsController extends ZrmSettingsController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }
}

class ZrmSettingsControllerTestCase extends CakeTestCase
{
    public $fixtures = ['app.zrm_setting'];

    public function startTest()
    {
        $this->ZrmSettings = new TestZrmSettingsController();
        $this->ZrmSettings->constructClasses();
    }

    public function endTest()
    {
        unset($this->ZrmSettings);
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
