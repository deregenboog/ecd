<?php

declare(strict_types=1);

namespace Tests\HsBundle\Entity;

use HsBundle\Entity\Betaling;
use HsBundle\Entity\Factuur;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BetalingTest extends KernelTestCase
{
    public function testToString()
    {
        $this->markTestSkipped();

        // set locale
        $kernel = static::bootKernel();
        $locale = $kernel->getContainer()->getParameter('locale');
        setlocale(LC_ALL, $locale);

        $factuur = $this->createMock(Factuur::class);
        $betaling = new Betaling($factuur);

        $betaling->setBedrag(123.45);
        $this->assertEquals('€ 123,45', (string) $betaling);

        $betaling->setBedrag(123.456789);
        $this->assertEquals('€ 123,46', (string) $betaling);

        $betaling->setBedrag(123.454321);
        $this->assertEquals('€ 123,45', (string) $betaling);

        // restore locale
        setlocale(LC_ALL, 'C');
    }

    public function testIsDeletable()
    {
        $betaling = new Betaling();

        $this->assertFalse($betaling->isDeletable());
    }
}
