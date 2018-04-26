<?php

namespace IzBundle\Export;

use IzBundle\Entity\IzVrijwilliger;
use AppBundle\Export\GenericExport;

class IzVrijwilligersExport extends GenericExport
{
    public function getProjectenOpenstaandeHulpaanbiedingen(IzVrijwilliger $izVrijwilliger)
    {
        $projecten = [];

        foreach ($izVrijwilliger->getHulpaanbiedingen() as $hulpaanbod) {
            if (!$hulpaanbod->isGekoppeld() && !$hulpaanbod->isAfgesloten()) {
                $projecten[] = $hulpaanbod->getProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getProjectenLopendeKoppelingen(IzVrijwilliger $izVrijwilliger)
    {
        $projecten = [];

        foreach ($izVrijwilliger->getHulpaanbiedingen() as $hulpaanbod) {
            if ($hulpaanbod->isGekoppeld() && !$hulpaanbod->isAfgesloten()) {
                $projecten[] = $hulpaanbod->getProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getProjectenAfgeslotenKoppelingen(IzVrijwilliger $izVrijwilliger)
    {
        $projecten = [];

        foreach ($izVrijwilliger->getHulpaanbiedingen() as $hulpaanbod) {
            if ($hulpaanbod->isGekoppeld() && $hulpaanbod->isAfgesloten()) {
                $projecten[] = $hulpaanbod->getProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getStartdatumEersteKoppeling(IzVrijwilliger $izVrijwilliger)
    {
        $data = [];

        foreach ($izVrijwilliger->getHulpaanbiedingen() as $hulpaanbod) {
            if ($hulpaanbod->isGekoppeld()) {
                $data[] = $hulpaanbod->getKoppelingStartdatum();
            }
        }

        if (0 === count($data)) {
            return;
        }

        usort($data, function (\DateTime $date1, \DateTime $date2) {
            if ($date1 < $date2) {
                return -1;
            }
            if ($date1 > $date2) {
                return 1;
            }

            return 0;
        });

        return $data[0];
    }

    public function getDatumLaatsteAfgeslotenKoppeling(IzVrijwilliger $izVrijwilliger)
    {
        $data = [];

        foreach ($izVrijwilliger->getHulpaanbiedingen() as $hulpaanbod) {
            if ($hulpaanbod->isGekoppeld() && $hulpaanbod->isAfgesloten()) {
                $data[] = $hulpaanbod->getKoppelingEinddatum();
            }
        }

        if (count($data) > 0) {
            usort($data, function (\DateTime $date1, \DateTime $date2) {
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

        foreach ($izVrijwilliger->getHulpaanbiedingen() as $hulpaanbod) {
            if ($hulpaanbod->getMedewerker()) {
                $medewerkers[] = $hulpaanbod->getMedewerker();
            }
        }

        return implode(', ', array_unique($medewerkers));
    }
}
