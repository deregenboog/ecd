<?php

namespace AppBundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\DateType;

class MysqlDateType extends DateType
{
    public function getName()
    {
        return 'mysql_date';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($platform instanceof MySqlPlatform && '0000-00-00' === $value) {
            return null;
        }

        return parent::convertToPHPValue($value, $platform);
    }
}
