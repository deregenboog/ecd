<?php

namespace AppBundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TimeType;

class MysqlTimeType extends TimeType
{
    public function getName(): string
    {
        return 'mysql_time';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
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
