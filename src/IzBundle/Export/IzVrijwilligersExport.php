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

    public function getDatumLaatsteAfgeslotenKoppeling(IzVrijwilliger $izVrijwilliger)
    {
        $data = [];

        foreach ($izVrijwilliger->getIzHulpaanbiedingen() as $izHulpaanbod) {
            if ($izHulpaanbod->isGekoppeld() && $izHulpaanbod->isAfgesloten()) {
                $data[] = $izHulpaanbod->getKoppelingEinddatum();
            }
        }

        if (count($data) > 0) {
            usort($data, function(\DateTime $date1, \DateTime $date2) {
                if ($date1 > $date2) {
                    return -1;
                }
                if ($date2 > $date1) {
                    return 1;
                }
                return 0;
            });

            return $data[0];
        }
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
