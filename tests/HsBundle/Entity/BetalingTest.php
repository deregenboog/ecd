<?php

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Betaling;
use HsBundle\Entity\Factuur;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BetalingTest extends KernelTestCase
{
    public function testToString()
    {
        if (!self::$kernel) {
            self::bootKernel();
        }

        // get and set locale
        $locale = setlocale(LC_ALL, 0);
        setlocale(LC_ALL, self::$kernel->getContainer()->getParameter('locale'));

        $factuur = $this->createMock(Factuur::class);
        $betaling = new Betaling($factuur);

        $betaling->setBedrag(123.45);
        $this->assertEquals('€ 123,45', (string) $betaling);

        $betaling->setBedrag(123.456789);
        $this->assertEquals('€ 123,46', (string) $betaling);

        $betaling->setBedrag(123.454321);
        $this->assertEquals('€ 123,45', (string) $betaling);

        // restore locale
        if ($locale) {
            setlocale(LC_ALL, $locale);
        }
    }

    public function testIsDeletable()
    {
        $betaling = new Betaling();

        $this->assertFalse($betaling->isDeletable());
    }
}
