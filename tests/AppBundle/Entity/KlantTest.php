<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Zrm;
use PHPUnit\Framework\TestCase;

class KlantTest extends TestCase
{
    public function testAddZrm()
    {
        $klant = new Klant();
        $zrm = Zrm::create();

        $klant->addZrm($zrm);

        $this->assertSame($klant, $zrm->getKlant());
        $this->assertContains($zrm, $klant->getZrms());
    }
}
