<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Vrijwilliger;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DisabledFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (in_array($targetEntity->getName(), [Klant::class,
            Vrijwilliger::class,
//            \OekBundle\Entity\Deelnemer::clasrrs,
//            \TwBundle\Entity\Deelnemer::class,
//            \ScipBundle\Entity\Deelnemer::class,
//            \DagbestedingBundle\Entity\Deelnemer::class,
//            \IzBundle\Entity\IzDeelnemer::class,
            ])) {
            return sprintf('(%s.disabled IS NULL OR %s.disabled = 0)', $targetTableAlias, $targetTableAlias);
        }

        return '';
    }
}
