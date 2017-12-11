<?php

/* ZrmSetting Test cases generated on: 2013-11-26 17:11:39 : 1385484879*/
App::import('Model', 'ZrmSetting');

class ZrmSettingTestCase extends CakeTestCase
{
    public $fixtures = ['app.zrm_setting'];

    public function startTest()
    {
        $this->ZrmSetting = &ClassRegistry::init('ZrmSetting');
    }

    public function endTest()
    {
        unset($this->ZrmSetting);
        ClassRegistry::flush();
    }
}
