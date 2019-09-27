<?php

namespace AppBundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TimeType;

class MysqlTimeType extends TimeType
{
    public function getName()
    {
        return 'mysql_time';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        try {
            return parent::convertToPHPValue($value, $platform);
        } catch (ConversionException $exception) {
            $matches = [];
            if ($platform instanceof MySqlPlatform
                && 1 === preg_match('/^-(\d{2}):(\d{2}):(\d{2})$/', $value, $matches)
            ) {
                array_shift($matches);
                $matches[0] = 24 - $matches[0];
                $value = implode(':', $matches);

                return parent::convertToPHPValue($value, $platform);
            }

            throw $exception;
        }
    }
}
