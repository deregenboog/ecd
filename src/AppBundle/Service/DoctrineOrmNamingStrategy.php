<?php

namespace AppBundle\Service;

use Doctrine\ORM\Mapping\DefaultNamingStrategy;

class DoctrineOrmNamingStrategy extends DefaultNamingStrategy
{
    /**
     * {@inheritdoc}
     */
    public function joinTableName($sourceEntity, $targetEntity, $propertyName = null): string
    {
        $prefix = '';
        $matches = [];

        if (preg_match('/^([A-z]*)Bundle/', $sourceEntity, $matches)) {
            $prefix = strtolower($matches[1]).'_';
        }

        return $prefix.parent::joinTableName($sourceEntity, $targetEntity, $propertyName);
    }
}
