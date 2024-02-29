<?php

namespace Tests\TwBundle\Entity;

use PHPUnit\Framework\TestCase;
use TwBundle\Entity\Pandeigenaar;
use TwBundle\Entity\PandeigenaarType;

class PandeigenaarTypeTest extends TestCase
{
    public function testIsDeletable()
    {
        $type = new PandeigenaarType();
        $this->assertTrue($type->isDeletable());

        $type->addPandeigenaar(new Pandeigenaar());
        $this->assertFalse($type->isDeletable());
    }

    public function testAddPandeigenaar()
    {
        $type = new PandeigenaarType();
        $eigenaar = new Pandeigenaar();
        $type->addPandeigenaar($eigenaar);
        $this->assertSame($type, $eigenaar->getPandeigenaarType());
    }
}
