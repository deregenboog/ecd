<?php

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Betaling;
use HsBundle\Entity\Factuur;

class BetalingTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $factuur = $this->createMock(Factuur::class);
        $betaling = new Betaling($factuur);

        $betaling->setBedrag(123.45);
        $this->assertEquals('€ 123,45', (string) $betaling);

        $betaling->setBedrag(123.456789);
        $this->assertEquals('€ 123,46', (string) $betaling);

        $betaling->setBedrag(123.454321);
        $this->assertEquals('€ 123,45', (string) $betaling);
    }

    public function testIsDeletable()
    {
        $betaling = new Betaling();

        $this->assertFalse($betaling->isDeletable());
    }
}
