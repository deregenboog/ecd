<?php

namespace ErOpUitBundle\Doctrine;

use AppBundle\Entity\Incident;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class IncidentenFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        $traits = $targetEntity->name;
        
        if (in_array($traits, [Incident::class])) {
            return sprintf('%s.discr = %s', $targetTableAlias, "'eropuit'");
        }

        return '';
    }
}
