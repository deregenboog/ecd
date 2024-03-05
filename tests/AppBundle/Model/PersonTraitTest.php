<?php

declare(strict_types=1);

namespace Tests\AppBundle\Model;

use AppBundle\Model\PersonTrait;
use PHPUnit\Framework\TestCase;

class PersonTraitTest extends TestCase
{
    public function testGetNaam()
    {
        $person = $this->getMockForTrait(PersonTrait::class);
        $person->setVoornaam('Piet');
        $this->assertEquals('Piet', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);

        $person = $this->getMockForTrait(PersonTrait::class);
        $person->setAchternaam('Jansen');
        $this->assertEquals('Jansen', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);

        $person = $this->getMockForTrait(PersonTrait::class);
        $person->setVoornaam('Piet')->setAchternaam('Jansen');
        $this->assertEquals('Jansen, Piet', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);

        $person = $this->getMockForTrait(PersonTrait::class);
        $person->setVoornaam('Piet')->setTussenvoegsel('van der')->setAchternaam('Jansen');
        $this->assertEquals('Jansen, Piet van der', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);

        $person = $this->getMockForTrait(PersonTrait::class);
        $person->setTussenvoegsel('van der')->setAchternaam('Jansen');
        $this->assertEquals('Jansen, van der', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);

        $person = $this->getMockForTrait(PersonTrait::class);
        $person->setRoepnaam('Piet')->setTussenvoegsel('van der')->setAchternaam('Jansen');
        $this->assertEquals('Jansen, (Piet) van der', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);

        $person = $this->getMockForTrait(PersonTrait::class);
        $person->setVoornaam('Jan')->setRoepnaam('Piet')->setTussenvoegsel('van der')->setAchternaam('Jansen');
        $this->assertEquals('Jansen, Jan (Piet) van der', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);
    }
}
