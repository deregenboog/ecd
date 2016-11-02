<?php
/* GroepsactiviteitenAfsluiting Test cases generated on: 2015-11-22 08:11:01 : 1448175901*/
App::import('Model', 'GroepsactiviteitenAfsluiting');

class GroepsactiviteitenAfsluitingTestCase extends CakeTestCase
{
    public $fixtures = array('app.groepsactiviteiten_afsluiting');

    public function startTest()
    {
        $this->GroepsactiviteitenAfsluiting =& ClassRegistry::init('GroepsactiviteitenAfsluiting');
    }

    public function endTest()
    {
        unset($this->GroepsactiviteitenAfsluiting);
        ClassRegistry::flush();
    }
}
