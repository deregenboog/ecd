<?php

namespace Tests\TwBundle\Entity;

use PHPUnit\Framework\TestCase;
use TwBundle\Entity\Document;
use TwBundle\Entity\Huuraanbod;
use TwBundle\Entity\Verhuurder;
use TwBundle\Entity\Verslag;

class VerhuurderTest extends TestCase
{
    public function testIsDeletable()
    {
        $verhuurder = new Verhuurder();
        $this->assertTrue($verhuurder->isDeletable());

        $verhuurder = new Verhuurder();
        $verhuurder->addHuuraanbod(new Huuraanbod());
        $this->assertFalse($verhuurder->isDeletable());

        $verhuurder = new Verhuurder();
        $verhuurder->addDocument(new Document());
        $this->assertFalse($verhuurder->isDeletable());

        $verhuurder = new Verhuurder();
        $verhuurder->addVerslag(new Verslag());
        $this->assertFalse($verhuurder->isDeletable());
    }
}
