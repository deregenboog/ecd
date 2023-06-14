<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Model\ActivatableTrait;
use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class ActiveFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        $traits = $targetEntity->getReflectionClass()->getTraitNames();

        if (in_array(ActivatableTrait::class, $traits) ){
            return sprintf('(%s.active = 1)', $targetTableAlias, $targetTableAlias);
        }

        return '';
    }
}
