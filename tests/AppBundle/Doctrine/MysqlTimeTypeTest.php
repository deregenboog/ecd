<?php

declare(strict_types=1);

namespace AppBundle\Doctrine;

use Doctrine\DBAL\Platforms\MariaDb1027Platform;
use Doctrine\DBAL\Platforms\MariaDBPlatform;
use Doctrine\DBAL\Platforms\MySQL57Platform;
use Doctrine\DBAL\Platforms\MySQL80Platform;
use PHPUnit\Framework\TestCase;

class MysqlTimeTypeTest extends TestCase
{
    public function testGetName()
    {
        $type = new MysqlTimeType();
        $this->assertEquals('mysql_time', $type->getName());
    }

    public function providerConvertToPHPValue()
    {
        return [
            ['00:00:00', new MySQL57Platform(), new \DateTime('1970-01-01T00:00:00')],
            ['00:00:00', new MySQL80Platform(), new \DateTime('1970-01-01T00:00:00')],
            ['00:00:00', new MariaDBPlatform(), new \DateTime('1970-01-01T00:00:00')],
            ['00:00:00', new MariaDb1027Platform(), new \DateTime('1970-01-01T00:00:00')],
            ['12:34:56', new MySQL57Platform(), new \DateTime('1970-01-01T12:34:56')],
            ['12:34:56', new MySQL80Platform(), new \DateTime('1970-01-01T12:34:56')],
            ['12:34:56', new MariaDBPlatform(), new \DateTime('1970-01-01T12:34:56')],
            ['12:34:56', new MariaDb1027Platform(), new \DateTime('1970-01-01T12:34:56')],
            ['-11:25:14', new MySQL57Platform(), new \DateTime('1970-01-01T13:25:14')],
            ['-11:25:14', new MySQL80Platform(), new \DateTime('1970-01-01T13:25:14')],
            ['-11:25:14', new MariaDBPlatform(), new \DateTime('1970-01-01T13:25:14')],
            ['-11:25:14', new MariaDb1027Platform(), new \DateTime('1970-01-01T13:25:14')],
        ];
    }

    /**
     * @dataProvider providerConvertToPHPValue
     */
    public function testConvertToPHPValue($value, $platform, $expected)
    {
        $type = new MysqlTimeType();
        $this->assertEquals($expected, $type->convertToPHPValue($value, $platform));
    }

    public function testRequiresSQLCommentHint()
    {
        $type = new MysqlTimeType();
        $platform = new MySQL80Platform();
        $this->assertEquals(true, $type->requiresSQLCommentHint($platform));
    }
}
