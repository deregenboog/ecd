<?php

namespace IzBundle\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\IzDeelnemer;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Entity\Koppeling;

class FoutieveInvoerFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (in_array($targetEntity->getName(), [IzDeelnemer::class, IzKlant::class, IzVrijwilliger::class])) {
            return sprintf('%s.iz_afsluiting_id IS NULL OR %s.iz_afsluiting_id <> 10', $targetTableAlias, $targetTableAlias);
        }

        if (in_array($targetEntity->getName(), [Koppeling::class, Hulpvraag::class, Hulpaanbod::class])) {
            return sprintf('%s.iz_eindekoppeling_id IS NULL OR %s.iz_eindekoppeling_id <> 10', $targetTableAlias, $targetTableAlias);
        }

        return '';
    }
}
