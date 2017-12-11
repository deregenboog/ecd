<?php

/* IzViaPersoon Test cases generated on: 2014-12-12 15:12:22 : 1418393602*/
App::import('Model', 'IzViaPersoon');

class IzViaPersoonTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_via_persoon'];

    public function startTest()
    {
        $this->IzViaPersoon = &ClassRegistry::init('IzViaPersoon');
    }

    public function endTest()
    {
        unset($this->IzViaPersoon);
        ClassRegistry::flush();
    }
}
