<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Klant;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class OverledenFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (in_array($targetEntity->getName(), [Klant::class])) {
            return sprintf('(%s.overleden IS NULL OR %s.overleden = 0)', $targetTableAlias, $targetTableAlias);
        }

        return '';
    }
}
