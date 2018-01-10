<?php

namespace IzBundle\Doctrine;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Vrijwilliger;
use IzBundle\Entity\IzDeelnemer;
use IzBundle\Entity\IzKoppeling;
use IzBundle\Entity\IzKlant;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Entity\IzHulpvraag;
use IzBundle\Entity\IzHulpaanbod;

class FoutieveInvoerFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (in_array($targetEntity->getName(), [IzDeelnemer::class, IzKlant::class, IzVrijwilliger::class])) {
            return sprintf('%s.iz_afsluiting_id IS NULL OR %s.iz_afsluiting_id <> 10', $targetTableAlias, $targetTableAlias);
        }

        if (in_array($targetEntity->getName(), [IzKoppeling::class, IzHulpvraag::class, IzHulpaanbod::class])) {
            return sprintf('%s.iz_eindekoppeling_id IS NULL OR %s.iz_eindekoppeling_id <> 10', $targetTableAlias, $targetTableAlias);
        }

        return '';
    }
}
