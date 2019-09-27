<?php

namespace Tests\AppBundle\Model;

use AppBundle\Model\NameTrait;
use PHPUnit\Framework\TestCase;

class NameTraitTest extends TestCase
{
    public function testGetNaam()
    {
        $person = $this->getMockForTrait(NameTrait::class);
        $person->setVoornaam('Piet');
        $this->assertEquals('Piet', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);

        $person = $this->getMockForTrait(NameTrait::class);
        $person->setAchternaam('Jansen');
        $this->assertEquals('Jansen', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);

        $person = $this->getMockForTrait(NameTrait::class);
        $person->setVoornaam('Piet')->setAchternaam('Jansen');
        $this->assertEquals('Piet Jansen', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);

        $person = $this->getMockForTrait(NameTrait::class);
        $person->setVoornaam('Piet')->setTussenvoegsel('van der')->setAchternaam('Jansen');
        $this->assertEquals('Piet van der Jansen', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);

        $person = $this->getMockForTrait(NameTrait::class);
        $person->setTussenvoegsel('van der')->setAchternaam('Jansen');
        $this->assertEquals('van der Jansen', $person->getNaam());
        $this->assertEquals($person->getNaam(), (string) $person);
    }

    public function testGetAchternaamCompleet()
    {
        $person = $this->getMockForTrait(NameTrait::class);
        $person->setVoornaam('Piet');
        $this->assertEquals('', $person->getAchternaamCompleet());

        $person = $this->getMockForTrait(NameTrait::class);
        $person->setAchternaam('Jansen');
        $this->assertEquals('Jansen', $person->getAchternaamCompleet());

        $person = $this->getMockForTrait(NameTrait::class);
        $person->setVoornaam('Piet')->setAchternaam('Jansen');
        $this->assertEquals('Jansen', $person->getAchternaamCompleet());

        $person = $this->getMockForTrait(NameTrait::class);
        $person->setVoornaam('Piet')->setTussenvoegsel('van der')->setAchternaam('Jansen');
        $this->assertEquals('Jansen, van der', $person->getAchternaamCompleet());

        $person = $this->getMockForTrait(NameTrait::class);
        $person->setTussenvoegsel('van der')->setAchternaam('Jansen');
        $this->assertEquals('Jansen, van der', $person->getAchternaamCompleet());
    }
}
