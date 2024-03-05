<?php

declare(strict_types=1);

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Declaratie;
use HsBundle\Entity\DeclaratieCategorie;
use PHPUnit\Framework\TestCase;

class DeclaratieCategorieTest extends TestCase
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
