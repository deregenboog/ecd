<?php

namespace IzBundle\Export;

use IzBundle\Entity\IzKlant;
use AppBundle\Export\GenericExport;

class IzKlantenExport extends GenericExport
{
    public function getProjecten(IzKlant $izKlant)
    {
        $projecten = [];

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            $projecten[] = $izHulpvraag->getIzProject();
        }

        return implode(', ', array_unique($projecten));
    }

    public function getMedewerkers(IzKlant $izKlant)
    {
        $medewerkers = [];

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            $medewerkers[] = $izHulpvraag->getMedewerker();
        }

        return implode(', ', array_unique($medewerkers));
    }
}
