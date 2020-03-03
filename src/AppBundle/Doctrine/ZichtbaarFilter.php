<?php

namespace AppBundle\Doctrine;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use AppBundle\Entity\Werkgebied;

class ZichtbaarFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        return '';//filter doet zn werk te goed en moet gewoon uitgeschakeld worden volgens mij. 20200302JTB
        if (in_array($targetEntity->getName(), [Werkgebied::class])) {
            return sprintf('(%s.zichtbaar = 1)', $targetTableAlias, $targetTableAlias);
        }

        return '';
    }
}
