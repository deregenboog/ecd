<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Vrijwilliger;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;



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
