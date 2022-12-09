<?php

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Declaratie;
use HsBundle\Entity\DeclaratieCategorie;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Klus;
use PHPUnit\Framework\TestCase;

class DeclaratieTest extends TestCase
{
    /**
     * @dataProvider provideSettersAndArguments
     */
    public function testSettingOnLockedDeclaratieResultsInException($method, $args)
    {
        $this->expectException(\HsBundle\Exception\InvoiceLockedException::class);

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
