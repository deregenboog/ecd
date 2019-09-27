<?php

namespace Tests\HsBundle\Entity;

use AppBundle\Entity\Medewerker;
use HsBundle\Entity\Activiteit;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Klus;
use PHPUnit\Framework\TestCase;

class ActiviteitTest extends TestCase
{
    public function testIsDeletable()
    {
        $activiteit = new Activiteit();
        $this->assertTrue($activiteit->isDeletable());

        $klus = new Klus(new Klant(), new Medewerker());
        $activiteit->getKlussen()->add($klus);
        $this->assertFalse($activiteit->isDeletable());

        $activiteit->getKlussen()->removeElement($klus);
        $this->assertTrue($activiteit->isDeletable());
    }
}
