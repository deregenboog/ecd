<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Werkgebied;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class ZichtbaarFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        return ''; // filter doet zn werk te goed en moet gewoon uitgeschakeld worden volgens mij. 20200302JTB
        if (in_array($targetEntity->getName(), [Werkgebied::class])) {
            return sprintf('(%s.zichtbaar = 1)', $targetTableAlias, $targetTableAlias);
        }

        return '';
    }
}
