<?php

namespace IzBundle\Entity;

use PHPUnit\Framework\TestCase;

class HulpTest extends TestCase
{
    public function testGetBeschikbareDagen()
    {
        $hulpvraag = new Hulpvraag();
        $hulpvraag->setBeschikbareDagen(['donderdag', 'dinsdag', 'unknown']);

        $this->assertEquals(
            ['dinsdag', 'donderdag'],
            $hulpvraag->getBeschikbareDagen()
        );
    }
}
