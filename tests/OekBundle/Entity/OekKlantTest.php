<?php

namespace Tests\OekBundle\Entity;

use OekBundle\Entity\OekKlant;
use OekBundle\Entity\OekAanmelding;
use OekBundle\Entity\OekAfsluiting;
use OekBundle\Entity\OekVerwijzingDoor;
use OekBundle\Entity\OekVerwijzingNaar;

class OekKlantTest extends \PHPUnit_Framework_TestCase
{
    public function testCanOpenDossier()
    {
        $oekKlant = new OekKlant();
        $oekKlant->addOekAanmelding($this->getOekAanmelding());

        $this->assertInstanceOf(OekAanmelding::class, $oekKlant->getOekDossierStatus());
    }

    /**
     * @expectedException \RunTimeException
     */
    public function testCannotCloseNonOpenDossier()
    {
        $oekKlant = new OekKlant();
        $oekKlant->addOekAfsluiting($this->getOekAfsluiting());
    }

    /**
     * @expectedException \RunTimeException
     */
    public function testCannotOpenOpenDossier()
    {
        $oekKlant = new OekKlant();
        $oekKlant->addOekAanmelding($this->getOekAanmelding());
        $oekKlant->addOekAanmelding($this->getOekAanmelding());
    }

    /**
     */
    public function testCanReopenClosedDossier()
    {
        $oekKlant = new OekKlant();
        $oekKlant->addOekAanmelding($this->getOekAanmelding());
        $oekKlant->addOekAfsluiting($this->getOekAfsluiting());
        $oekKlant->addOekAanmelding($this->getOekAanmelding());
    }

    /**
     * @expectedException \RunTimeException
     */
    public function testCannotCloseClosedDossier()
    {
        $oekKlant = new OekKlant();
        $oekKlant->addOekAanmelding($this->getOekAanmelding());
        $oekKlant->addOekAfsluiting($this->getOekAfsluiting());
        $oekKlant->addOekAfsluiting($this->getOekAfsluiting());
    }

    private function getOekAanmelding()
    {
        return new OekAanmelding(new \DateTime(), new OekVerwijzingDoor());
    }

    private function getOekAfsluiting()
    {
        return new OekAfsluiting(new \DateTime(), new OekVerwijzingNaar());
    }
}
