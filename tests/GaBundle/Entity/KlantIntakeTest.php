<?php

namespace Tests\GaBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Zrm;
use GaBundle\Entity\KlantIntake;
use PHPUnit\Framework\TestCase;

class KlantIntakeTest extends TestCase
{
    public function testSetZrm()
    {
        $klant = new Klant();
        $zrm = Zrm::create();

        $intake = new KlantIntake($klant);
        $intake->setZrm($zrm);

        $this->assertSame($intake->getKlant(), $zrm->getKlant());
        $this->assertContains($zrm, $klant->getZrms());
    }
}
