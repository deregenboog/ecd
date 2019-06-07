<?php

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Betaling;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Herinnering;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Registratie;
use PHPUnit\Framework\TestCase;

class FactuurTest extends TestCase
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
        $lastDayOfThisMonth = new \DateTime('last day of this month');
        $lastDayOfNextMonth = new \DateTime('last day of next month');

        $factuur = new Factuur(new Klant());
        $this->assertEquals($lastDayOfThisMonth->format('Y-m-d'), $factuur->getDatum()->format('Y-m-d'));

        $declaratie = new Declaratie();
        $declaratie->setDatum(new \DateTime('first day of this month'));
        $factuur->addDeclaratie($declaratie);
        $this->assertEquals($lastDayOfThisMonth->format('Y-m-d'), $factuur->getDatum()->format('Y-m-d'));

        $registratie = new Registratie();
        $registratie->setDatum(new \DateTime('first day of next month'));
        $factuur->addRegistratie($registratie);
        $this->assertEquals($lastDayOfNextMonth->format('Y-m-d'), $factuur->getDatum()->format('Y-m-d'));
    }
}
