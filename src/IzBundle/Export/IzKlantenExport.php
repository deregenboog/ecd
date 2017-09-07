<?php

namespace IzBundle\Export;

use IzBundle\Entity\IzKlant;
use AppBundle\Export\GenericExport;

class IzKlantenExport extends GenericExport
{
    public function getProjectenOpenstaandeHulpvragen(IzKlant $izKlant)
    {
        $projecten = [];

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            if (!$izHulpvraag->isGekoppeld() && !$izHulpvraag->isAfgesloten()) {
                $projecten[] = $izHulpvraag->getIzProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getProjectenLopendeKoppelingen(IzKlant $izKlant)
    {
        $projecten = [];

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            if ($izHulpvraag->isGekoppeld() && !$izHulpvraag->isAfgesloten()) {
                $projecten[] = $izHulpvraag->getIzProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getProjectenAfgeslotenKoppelingen(IzKlant $izKlant)
    {
        $projecten = [];

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            if ($izHulpvraag->isGekoppeld() && $izHulpvraag->isAfgesloten()) {
                $projecten[] = $izHulpvraag->getIzProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getMedewerkers(IzKlant $izKlant)
    {
        $medewerkers = [];

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            if ($izHulpvraag->getMedewerker()) {
                $medewerkers[] = $izHulpvraag->getMedewerker();
            }
        }

        return implode(', ', array_unique($medewerkers));
    }
}
