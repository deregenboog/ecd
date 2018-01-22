<?php

namespace Tests\HsBundle\Entity;

use AppBundle\Entity\Medewerker;
use HsBundle\Entity\Memo;

class MemoTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $medewerker = $this->createMock(Medewerker::class);
        $medewerker->method('__toString')->willReturn('Piet Jansen');

        $memo = new Memo($medewerker);
        $memo->setOnderwerp('Dit is het onderwerp');
        $this->assertEquals('Dit is het onderwerp (Piet Jansen, '.date('d-m-Y').')', (string) $memo);
    }
}
