<?php

/* Klantinventarisatie Test cases generated on: 2014-05-08 17:05:42 : 1399563282*/
App::import('Model', 'Klantinventarisatie');

class KlantinventarisatieTestCase extends CakeTestCase
{
    public $fixtures = array('app.klantinventarisatie');

    public function startTest()
    {
        $this->Klantinventarisatie = &ClassRegistry::init('Klantinventarisatie');
    }

    public function endTest()
    {
        unset($this->Klantinventarisatie);
        ClassRegistry::flush();
    }
}
