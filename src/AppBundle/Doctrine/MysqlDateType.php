<?php

namespace AppBundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\DateType;

class MysqlDateType extends DateType
{
    public function getName(): string
    {
        return 'mysql_date';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?\DateTimeInterface
    {
        if ($platform instanceof MySQLPlatform && ('1970-01-01' === $value || '0000-00-00' === $value)) {
            return null;
        }

        return parent::convertToPHPValue($value, $platform);
    }

    /**
     * If this Doctrine Type maps to an already mapped database type,
     * reverse schema engineering can't tell them apart. You need to mark
     * one of those types as commented, which will have Doctrine use an SQL
     * comment to typehint the actual Doctrine Type.
     *
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
