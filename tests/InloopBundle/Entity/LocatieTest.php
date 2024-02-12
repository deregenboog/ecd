<?php

declare(strict_types=1);

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

        // Monday afternoon open
        $this->assertTrue($locatie->isOpen(new \DateTime('2018-10-08 15:00:00')));
        // Tuesday afternoon closed
        $this->assertNotTrue($locatie->isOpen(new \DateTime('2018-10-09 15:00:00')));

        $locatie = new Locatie();
        $locatie->setLocatietijden([
            (new Locatietijd())
                ->setDagVanDeWeek(1) // Monday night untill Tuesday morning
                ->setOpeningstijd(new \DateTime('20:00:00'))
                ->setSluitingstijd(new \DateTime('10:00:00')),
        ]);

        // Monday morning closed
        $this->assertNotTrue($locatie->isOpen(new \DateTime('2018-10-08 02:00:00')));
        // Monday afternoon closed
        $this->assertNotTrue($locatie->isOpen(new \DateTime('2018-10-08 15:00:00')));
        // Tuesday morning open
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

        // Sunday at 19:00 closed
        $this->assertNotTrue($locatie->isOpen(new \DateTime('2018-10-07 19:00:00')));
        // Sunday at 20:00 open
        $this->assertTrue($locatie->isOpen(new \DateTime('2018-10-07 20:00:00')));
        // Monday morning open
        $this->assertTrue($locatie->isOpen(new \DateTime('2018-10-08 02:00:00')));
        // Monday afternoon closed
        $this->assertNotTrue($locatie->isOpen(new \DateTime('2018-10-08 15:00:00')));
        // Tuesday morning open
        $this->assertTrue($locatie->isOpen(new \DateTime('2018-10-09 02:00:00')));
        // Tuesday afternoon closed
        $this->assertNotTrue($locatie->isOpen(new \DateTime('2018-10-09 15:00:00')));
    }
}
