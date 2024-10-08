<?php

declare(strict_types=1);

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Activiteit;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Registratie;
use HsBundle\Entity\Vrijwilliger;
use PHPUnit\Framework\TestCase;

class RegistratieTest extends TestCase
{
    /**
     * @dataProvider provideSettersAndArguments
     */
    public function testSettingOnLockedRegistratieResultsInException($method, $args)
    {
        $this->expectException(\HsBundle\Exception\InvoiceLockedException::class);

        $factuur = new Factuur(new Klant());

        $registratie = new Registratie();
        $factuur->addRegistratie($registratie);
        $factuur->lock();

        call_user_func_array([$registratie, $method], $args);
    }

    public function provideSettersAndArguments()
    {
        return [
            ['setActiviteit', [new Activiteit()]],
            ['setArbeider', [new Dienstverlener()]],
            ['setArbeider', [new Vrijwilliger()]],
            ['setDatum', [new \DateTime()]],
            ['setEind', [new \DateTime()]],
            ['setFactuur', [new Factuur(new Klant())]],
            ['setKlus', [new Klus()]],
            ['setReiskosten', [15.0]],
            ['setStart', [new \DateTime()]],
        ];
    }
}
