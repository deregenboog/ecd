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

        $memo = new Memo();
        $this->assertEquals(date(' d-m-Y H:i:s'), (string) $memo);

        $memo = new Memo($medewerker);
        $this->assertEquals('Piet Jansen' . date(' d-m-Y H:i:s'), (string) $memo);
    }
}
