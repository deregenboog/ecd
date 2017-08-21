<?php

/* GroepsactiviteitenVerslag Test cases generated on: 2014-05-06 18:05:56 : 1399393016*/
App::import('Model', 'GroepsactiviteitenVerslag');

class GroepsactiviteitenVerslagTestCase extends CakeTestCase
{
    public $fixtures = ['app.groepsactiviteiten_verslag', 'app.medewerker', 'app.ldap_user'];

    public function startTest()
    {
        $this->GroepsactiviteitenVerslag = &ClassRegistry::init('GroepsactiviteitenVerslag');
    }

    public function endTest()
    {
        unset($this->GroepsactiviteitenVerslag);
        ClassRegistry::flush();
    }
}
