<?php

namespace IzBundle\Export;

use IzBundle\Entity\IzKlant;
use AppBundle\Export\GenericExport;

class IzKlantenExport extends GenericExport
{
    public function getOpenstaandeProjecten(IzKlant $izKlant)
    {
        $projecten = [];

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            if ($izHulpvraag->getIzHulpaanbod() === null) {
                $projecten[] = $izHulpvraag->getIzProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getLopendeProjecten(IzKlant $izKlant)
    {
        $projecten = [];

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            if ($izHulpvraag->getIzHulpaanbod()
                && ($izHulpvraag->getKoppelingEinddatum() === null || $izHulpvraag->getKoppelingEinddatum() > new \DateTime())
            ) {
                $projecten[] = $izHulpvraag->getIzProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getAfgeslotenProjecten(IzKlant $izKlant)
    {
        $projecten = [];

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            if ($izHulpvraag->getIzHulpaanbod()
                && ($izHulpvraag->getKoppelingEinddatum() <= new \DateTime())
            ) {
                $projecten[] = $izHulpvraag->getIzProject();
            }
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
