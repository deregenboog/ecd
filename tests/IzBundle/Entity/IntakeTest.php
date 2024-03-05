<?php

declare(strict_types=1);

namespace Tests\IzBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Zrm;
use IzBundle\Entity\Intake;
use IzBundle\Entity\IzKlant;
use PHPUnit\Framework\TestCase;

class IntakeTest extends TestCase
{
    public function testSetZrm()
    {
        $this->markTestSkipped();

        $klant = new Klant();
        $zrm = Zrm::create();

        $izDeelnemer = new IzKlant($klant);
        $intake = new Intake();
        $intake->setIzDeelnemer($izDeelnemer)->addZrm($zrm);

        $this->assertSame($izDeelnemer->getKlant(), $zrm->getKlant());
        $this->assertContains($zrm, $klant->getZrms());
    }
}
