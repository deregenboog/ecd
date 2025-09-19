<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Incident;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class IncidentenFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        $traits = $targetEntity->name;
        if (in_array($traits, [Incident::class])) {
            $discr = $this->getParameter('discr') ?? false;
            if ($discr) {
                return sprintf('%s.discr = %s', $targetTableAlias, "$discr");
            }
        }

        return '';
    }
}
