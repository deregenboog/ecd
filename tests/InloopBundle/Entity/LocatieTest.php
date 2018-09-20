<?php

namespace Tests\InloopBundle\Entity;

use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Locatietijd;
use PHPUnit\Framework\TestCase;

class LocatieTest extends TestCase
{
    public function testIsOpen()
    {
        $locatie = new Locatie();
        $locatie->setLocatietijden([
            (new Locatietijd())
                ->setDagVanDeWeek(1) // Monday
                ->setOpeningstijd(new \DateTime('10:00:00'))
                ->setSluitingstijd(new \DateTime('20:00:00')),
        ]);

        $this->assertTrue($locatie->isOpen(new \DateTime('2018-10-08 15:00:00')));
        $this->assertFalse($locatie->isOpen(new \DateTime('2018-10-09 15:00:00')));

        $locatie = new Locatie();
        $locatie->setLocatietijden([
            (new Locatietijd())
                ->setDagVanDeWeek(1) // Monday night untill Tuesday morning
                ->setOpeningstijd(new \DateTime('20:00:00'))
                ->setSluitingstijd(new \DateTime('10:00:00')),
        ]);

        $this->assertFalse($locatie->isOpen(new \DateTime('2018-10-08 02:00:00')));
        $this->assertFalse($locatie->isOpen(new \DateTime('2018-10-08 15:00:00')));
        $this->assertTrue($locatie->isOpen(new \DateTime('2018-10-09 02:00:00')));

        $locatie = new Locatie();
        $locatie->setLocatietijden([
            (new Locatietijd())
                ->setDagVanDeWeek(0) // Sunday night untill Monday morning
                ->setOpeningstijd(new \DateTime('20:00:00'))
                ->setSluitingstijd(new \DateTime('10:00:00')),
            (new Locatietijd())
                ->setDagVanDeWeek(1) // Monday night untill Tuesday morning
                ->setOpeningstijd(new \DateTime('20:00:00'))
                ->setSluitingstijd(new \DateTime('10:00:00')),
        ]);

        $this->assertTrue($locatie->isOpen(new \DateTime('2018-10-08 02:00:00')));
        $this->assertFalse($locatie->isOpen(new \DateTime('2018-10-08 15:00:00')));
        $this->assertTrue($locatie->isOpen(new \DateTime('2018-10-09 02:00:00')));
    }
}
