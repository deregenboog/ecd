<?php

namespace IzBundle\Export;

use IzBundle\Entity\IzVrijwilliger;
use AppBundle\Export\GenericExport;

class IzVrijwilligersExport extends GenericExport
{
    public function getProjecten(IzVrijwilliger $izVrijwilliger)
    {
        $projecten = [];

        foreach ($izVrijwilliger->getIzHulpaanbiedingen() as $izHulpaanbod) {
            $projecten[] = $izHulpaanbod->getIzProject();
        }

        return implode(', ', array_unique($projecten));
    }

    public function getMedewerkers(IzVrijwilliger $izVrijwilliger)
    {
        $medewerkers = [];

        foreach ($izVrijwilliger->getIzHulpaanbiedingen() as $izHulpaanbod) {
            $medewerkers[] = $izHulpaanbod->getMedewerker();
        }

        return implode(', ', array_unique($medewerkers));
    }
}
