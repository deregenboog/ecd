<?php

declare(strict_types=1);

namespace Tests\AppBundle\Util;

use AppBundle\Util\DateTimeUtil;
use PHPUnit\Framework\TestCase;

class DateTimeUtilTest extends TestCase
{
    public function testCombine()
    {
        $date = new \DateTime('2019-03-25');
        $time = new \DateTime('11:23:50');
        $dateTime = new \DateTime('2019-03-25 11:23:50');

        $this->assertEquals($dateTime, DateTimeUtil::combine($date, $time));
    }

    public function testDayOfWeek()
    {
        $days = ['zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag'];
        $expected = array_merge($days, $days);

        $actual = [];
        for ($i = 0; $i < 14; ++$i) {
            $actual[] = DateTimeUtil::dayOfWeek($i);
        }

        $this->assertEquals($expected, $actual);
    }
}
