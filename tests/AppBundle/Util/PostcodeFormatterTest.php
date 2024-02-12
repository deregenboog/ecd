<?php

declare(strict_types=1);

namespace Tests\AppBundle\Util;

use AppBundle\Util\PostcodeFormatter;
use PHPUnit\Framework\TestCase;

class PostcodeFormatterTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testFormat($input, $ouput)
    {
        $this->assertEquals($ouput, PostcodeFormatter::format($ouput));
    }

    public function dataProvider()
    {
        return [
            ['1234aa', '1234AA'],
            ['1234 aa', '1234AA'],
            ['1234Aa', '1234AA'],
            ['1234 Aa', '1234AA'],
            ['1234AA', '1234AA'],
            ['1234 AA', '1234AA'],
        ];
    }
}
