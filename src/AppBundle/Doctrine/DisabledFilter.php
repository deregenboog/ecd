<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Vrijwilliger;
use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DisabledFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (in_array($targetEntity->getName(), [Klant::class, Vrijwilliger::class])) {
            return sprintf('%s.disabled = 0', $targetTableAlias);
        }

        return '';
    }
}
