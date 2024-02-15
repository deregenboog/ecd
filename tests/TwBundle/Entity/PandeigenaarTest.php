<?php

namespace Tests\TwBundle\Entity;

use PHPUnit\Framework\TestCase;
use TwBundle\Entity\Pandeigenaar;
use TwBundle\Entity\Verhuurder;

class PandeigenaarTest extends TestCase
{
    public function testIsDeletable()
    {
        $type = new Pandeigenaar();
        $this->assertTrue($type->isDeletable());

        $type->addVerhuurder(new Verhuurder());
        $this->assertFalse($type->isDeletable());
    }
}
