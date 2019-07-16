<?php

namespace Tests\OekBundle\Entity;

use OekBundle\Entity\Aanmelding;
use OekBundle\Entity\Afsluiting;
use OekBundle\Entity\Deelnemer;
use OekBundle\Entity\VerwijzingDoor;
use OekBundle\Entity\VerwijzingNaar;
use PHPUnit\Framework\TestCase;

class DeelnemerTest extends TestCase
{
    public function testCanOpenDossier()
    {
        $deelnemer = new Deelnemer();
        $deelnemer->addAanmelding($this->getAanmelding());

        $this->assertInstanceOf(Aanmelding::class, $deelnemer->getDossierStatus());
    }

    /**
     * @expectedException \RunTimeException
     */
    public function testCannotCloseNonOpenDossier()
    {
        $deelnemer = new Deelnemer();
        $deelnemer->addAfsluiting($this->getAfsluiting());
    }

    /**
     * @expectedException \RunTimeException
     */
    public function testCannotOpenOpenDossier()
    {
        $deelnemer = new Deelnemer();
        $deelnemer->addAanmelding($this->getAanmelding());
        $deelnemer->addAanmelding($this->getAanmelding());
    }

    public function testCanReopenClosedDossier()
    {
        $deelnemer = new Deelnemer();
        $deelnemer->addAanmelding($this->getAanmelding());
        $deelnemer->addAfsluiting($this->getAfsluiting());
        $deelnemer->addAanmelding($this->getAanmelding());

        $this->assertInstanceOf(Aanmelding::class, $deelnemer->getDossierStatus());
    }

    /**
     * @expectedException \RunTimeException
     */
    public function testCannotCloseClosedDossier()
    {
        $deelnemer = new Deelnemer();
        $deelnemer->addAanmelding($this->getAanmelding());
        $deelnemer->addAfsluiting($this->getAfsluiting());
        $deelnemer->addAfsluiting($this->getAfsluiting());
    }

    private function getAanmelding()
    {
        return new Aanmelding(new \DateTime(), new VerwijzingDoor());
    }

    private function getAfsluiting()
    {
        return new Afsluiting(new \DateTime(), new VerwijzingNaar());
    }
}
