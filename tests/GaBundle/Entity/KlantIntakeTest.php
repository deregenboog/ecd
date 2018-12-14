<?php

namespace Tests\GaBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Zrm;
use GaBundle\Entity\Intake;
use GaBundle\Entity\Klantdossier;
use PHPUnit\Framework\TestCase;

class KlantIntakeTest extends TestCase
{
    public function testSetZrm()
    {
        $klant = new Klant();
        $zrm = Zrm::create();

        $dossier = new Klantdossier($klant);

        $intake = new Intake($dossier);
        $intake->setZrm($zrm);

        $this->assertSame($intake->getDossier()->getKlant(), $zrm->getKlant());
        $this->assertContains($zrm, $klant->getZrms());
    }
}
