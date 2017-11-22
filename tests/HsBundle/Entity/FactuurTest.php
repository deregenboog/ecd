<?php

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\Registratie;

class FactuurTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \HsBundle\Exception\HsException
     */
    public function testAddingRegistratieToLockedFactuurResultsInException()
    {
        $factuur = new Factuur(new Klant());
        $factuur->lock();
        $factuur->addRegistratie(new Registratie());
    }

    /**
     * @expectedException \HsBundle\Exception\HsException
     */
    public function testAddingDeclaratieToLockedFactuurResultsInException()
    {
        $factuur = new Factuur(new Klant());
        $factuur->lock();
        $factuur->addDeclaratie(new Declaratie());
    }
}
