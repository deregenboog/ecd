<?php

namespace IzBundle\Export;

use IzBundle\Entity\IzVrijwilliger;
use AppBundle\Export\GenericExport;

class IzVrijwilligersExport extends GenericExport
{
    public function getOpenstaandeProjecten(IzVrijwilliger $izVrijwilliger)
    {
        $projecten = [];

        foreach ($izVrijwilliger->getIzHulpaanbiedingen() as $izHulpaanbod) {
            if ($izHulpaanbod->getIzHulpvraag() === null) {
                $projecten[] = $izHulpaanbod->getIzProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getLopendeProjecten(IzVrijwilliger $izVrijwilliger)
    {
        $projecten = [];

        foreach ($izVrijwilliger->getIzHulpaanbiedingen() as $izHulpaanbod) {
            if ($izHulpaanbod->getIzHulpvraag()
                && ($izHulpaanbod->getKoppelingEinddatum() === null || $izHulpaanbod->getKoppelingEinddatum() > new \DateTime())
            ) {
                $projecten[] = $izHulpaanbod->getIzProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getAfgeslotenProjecten(IzVrijwilliger $izVrijwilliger)
    {
        $projecten = [];

        foreach ($izVrijwilliger->getIzHulpaanbiedingen() as $izHulpaanbod) {
            if ($izHulpaanbod->getIzHulpvraag()
                && ($izHulpaanbod->getKoppelingEinddatum() <= new \DateTime())
            ) {
                $projecten[] = $izHulpaanbod->getIzProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getMedewerkers(IzVrijwilliger $izVrijwilliger)
    {
        $medewerkers = [];

        foreach ($izVrijwilliger->getIzHulpaanbiedingen() as $izHulpaanbod) {
            if ($izHulpaanbod->getMedewerker()) {
                $medewerkers[] = $izHulpaanbod->getMedewerker();
            }
        }

        return implode(', ', array_unique($medewerkers));
    }
}
