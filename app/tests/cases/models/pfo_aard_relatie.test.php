<?php
/* PfoAardRelatie Test cases generated on: 2013-06-09 19:06:00 : 1370799660*/
App::import('Model', 'PfoAardRelatie');

class PfoAardRelatieTestCase extends CakeTestCase
{
    public $fixtures = array('app.pfo_aard_relatie');

    public function startTest()
    {
        $this->PfoAardRelatie =& ClassRegistry::init('PfoAardRelatie');
    }

    public function endTest()
    {
        unset($this->PfoAardRelatie);
        ClassRegistry::flush();
    }
}
