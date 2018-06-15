<?php

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Declaratie;
use HsBundle\Entity\DeclaratieCategorie;

class DeclaratieCategorieTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $declaratieCategorie = new DeclaratieCategorie();

        $declaratieCategorie->setNaam('Naam123');
        $this->assertEquals('Naam123', (string) $declaratieCategorie);
    }

    public function testIsDeletable()
    {
        $declaratieCategorie = new DeclaratieCategorie();
        $this->assertTrue($declaratieCategorie->isDeletable());

        $declaratie = $this->createMock(Declaratie::class);
        $declaratieCategorie->addDeclaratie($declaratie);
        $this->assertFalse($declaratieCategorie->isDeletable());
    }
}
