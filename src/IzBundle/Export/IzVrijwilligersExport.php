<?php

namespace IzBundle\Export;

use IzBundle\Entity\IzVrijwilliger;
use AppBundle\Export\GenericExport;

class IzVrijwilligersExport extends GenericExport
{
    public function getProjectenOpenstaandeHulpaanbiedingen(IzVrijwilliger $izVrijwilliger)
    {
        $projecten = [];

        foreach ($izVrijwilliger->getIzHulpaanbiedingen() as $izHulpaanbod) {
            if (!$izHulpaanbod->isGekoppeld() && !$izHulpaanbod->isAfgesloten()) {
                $projecten[] = $izHulpaanbod->getIzProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getProjectenLopendeKoppelingen(IzVrijwilliger $izVrijwilliger)
    {
        $projecten = [];

        foreach ($izVrijwilliger->getIzHulpaanbiedingen() as $izHulpaanbod) {
            if ($izHulpaanbod->isGekoppeld() && !$izHulpaanbod->isAfgesloten()) {
                $projecten[] = $izHulpaanbod->getIzProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getProjectenAfgeslotenKoppelingen(IzVrijwilliger $izVrijwilliger)
    {
        $projecten = [];

        foreach ($izVrijwilliger->getIzHulpaanbiedingen() as $izHulpaanbod) {
            if ($izHulpaanbod->isGekoppeld() && $izHulpaanbod->isAfgesloten()) {
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
