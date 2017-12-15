<?php

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Registratie;
use HsBundle\Exception\InvoiceLockedException;
use HsBundle\Entity\Betaling;
use HsBundle\Entity\Herinnering;

class FactuurTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \HsBundle\Exception\InvoiceLockedException
     */
    public function testAddingRegistratieToLockedFactuurResultsInException()
    {
        $factuur = new Factuur(new Klant());
        $factuur->lock();
        $factuur->addRegistratie(new Registratie());
    }

    /**
     * @expectedException \HsBundle\Exception\InvoiceLockedException
     */
    public function testAddingDeclaratieToLockedFactuurResultsInException()
    {
        $factuur = new Factuur(new Klant());
        $factuur->lock();
        $factuur->addDeclaratie(new Declaratie());
    }

    /**
     * @expectedException \HsBundle\Exception\InvoiceNotLockedException
     */
    public function testAddingBetalingToUnlockedFactuurResultsInException()
    {
        $factuur = new Factuur(new Klant());
        $factuur->addBetaling(new Betaling());
    }

    /**
     * @expectedException \HsBundle\Exception\InvoiceNotLockedException
     */
    public function testAddingHerinneringToUnlockedFactuurResultsInException()
    {
        $factuur = new Factuur(new Klant());
        $factuur->addHerinnering(new Herinnering());
    }

    public function testAddingDeclaratieOrRegistratieResultsInDatumUpdate()
    {
        $factuur = new Factuur(new Klant());
        $this->assertEquals(new \DateTime('today'), $factuur->getDatum());

        $declaratie = new Declaratie();
        $declaratie->setDatum(new \DateTime('yesterday'));
        $factuur->addDeclaratie($declaratie);
        $this->assertEquals(new \DateTime('yesterday'), $factuur->getDatum());

        $registratie = new Registratie();
        $registratie->setDatum(new \DateTime('+2 days'));
        $factuur->addRegistratie($registratie);
        $this->assertEquals(new \DateTime('+2 days'), $factuur->getDatum());

        $declaratie = new Declaratie();
        $declaratie->setDatum(new \DateTime('tomorrow'));
        $factuur->addDeclaratie($declaratie);
        $this->assertEquals(new \DateTime('+2 days'), $factuur->getDatum());
    }
}
