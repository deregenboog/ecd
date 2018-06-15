<?php

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Declaratie;
use HsBundle\Entity\DeclaratieCategorie;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Klus;

class DeclaratieTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideSettersAndArguments
     * @expectedException \HsBundle\Exception\InvoiceLockedException
     */
    public function testSettingOnLockedDeclaratieResultsInException($method, $args)
    {
        $factuur = new Factuur(new Klant());

        $declaratie = new Declaratie();
        $factuur->addDeclaratie($declaratie);
        $factuur->lock();

        call_user_func_array([$declaratie, $method], $args);
    }

    public function provideSettersAndArguments()
    {
        return [
            ['setBedrag', [15.0]],
            ['setDatum', [new \DateTime()]],
            ['setDeclaratieCategorie', [new DeclaratieCategorie()]],
            ['setFactuur', [new Factuur(new Klant())]],
            ['setKlus', [new Klus()]],
            ['setInfo', ['Some information']],
        ];
    }
}
