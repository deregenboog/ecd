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
    public function testAddingRegistratieToLockedFactuurResultsInException()
    {
        $this->expectException(\HsBundle\Exception\InvoiceLockedException::class);

        $factuur = new Factuur(new Klant());
        $factuur->lock();
        $factuur->addRegistratie(new Registratie());
    }

    public function testAddingDeclaratieToLockedFactuurResultsInException()
    {
        $this->expectException(\HsBundle\Exception\InvoiceLockedException::class);

        $factuur = new Factuur(new Klant());
        $factuur->lock();
        $factuur->addDeclaratie(new Declaratie());
    }

    public function testAddingBetalingToUnlockedFactuurResultsInException()
    {
        $this->expectException(\HsBundle\Exception\InvoiceNotLockedException::class);

        $factuur = new Factuur(new Klant());
        $factuur->addBetaling(new Betaling());
    }

    public function testAddingHerinneringToUnlockedFactuurResultsInException()
    {
        $this->expectException(\HsBundle\Exception\InvoiceNotLockedException::class);

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

        //See note in Factuur.php about #824.
        $this->assertEquals($lastDayOfNextMonth->format('Y-m-d'), $factuur->getDatum()->format('Y-m-d'));


    }
}
