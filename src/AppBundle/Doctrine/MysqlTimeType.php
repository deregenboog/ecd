<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Vrijwilliger;
use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TimeType;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\MySqlPlatform;

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
