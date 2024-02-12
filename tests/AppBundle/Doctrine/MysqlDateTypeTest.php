<?php

declare(strict_types=1);

namespace AppBundle\Doctrine;

use Doctrine\DBAL\Platforms\MariaDb1027Platform;
use Doctrine\DBAL\Platforms\MariaDBPlatform;
use Doctrine\DBAL\Platforms\MySQL57Platform;
use Doctrine\DBAL\Platforms\MySQL80Platform;
use PHPUnit\Framework\TestCase;

class MysqlDateTypeTest extends TestCase
{
    public function testGetName()
    {
        $type = new MysqlDateType();
        $this->assertEquals('mysql_date', $type->getName());
    }

    public function providerConvertToPHPValue()
    {
        return [
            ['0000-00-00', new MySQL57Platform(), null],
            ['0000-00-00', new MySQL80Platform(), null],
            ['0000-00-00', new MariaDBPlatform(), null],
            ['0000-00-00', new MariaDb1027Platform(), null],
            ['1970-01-01', new MySQL57Platform(), null],
            ['1970-01-01', new MySQL80Platform(), null],
            ['1970-01-01', new MariaDBPlatform(), null],
            ['1970-01-01', new MariaDb1027Platform(), null],
            ['2024-02-07', new MySQL57Platform(), new \DateTime('2024-02-07')],
            ['2024-02-07', new MySQL80Platform(), new \DateTime('2024-02-07')],
            ['2024-02-07', new MariaDBPlatform(), new \DateTime('2024-02-07')],
            ['2024-02-07', new MariaDb1027Platform(), new \DateTime('2024-02-07')],
        ];
    }

    /**
     * @dataProvider providerConvertToPHPValue
     */
    public function testConvertToPHPValue($value, $platform, $expected)
    {
        $type = new MysqlDateType();
        $this->assertEquals($expected, $type->convertToPHPValue($value, $platform));
    }

    public function testRequiresSQLCommentHint()
    {
        $type = new MysqlDateType();
        $platform = new MySQL80Platform();
        $this->assertEquals(true, $type->requiresSQLCommentHint($platform));
    }
}
