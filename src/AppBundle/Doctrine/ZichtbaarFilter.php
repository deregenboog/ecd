<?php

namespace AppBundle\Doctrine;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use AppBundle\Entity\Werkgebied;

class ZichtbaarFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (in_array($targetEntity->getName(), [Werkgebied::class])) {
            return sprintf('(%s.zichtbaar = 1)', $targetTableAlias, $targetTableAlias);
        }

        return '';
    }
}
