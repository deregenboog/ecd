<?php

namespace IzBundle\Export;

use IzBundle\Entity\IzKlant;
use AppBundle\Export\GenericExport;

class IzKlantenExport extends GenericExport
{
    public function getProjectenOpenstaandeHulpvragen(IzKlant $izKlant)
    {
        $projecten = [];

        foreach ($izKlant->getIzHulpvragen() as $hulpvraag) {
            if (!$hulpvraag->isGekoppeld() && !$hulpvraag->isAfgesloten()) {
                $projecten[] = $hulpvraag->getProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getProjectenLopendeKoppelingen(IzKlant $izKlant)
    {
        $projecten = [];

        foreach ($izKlant->getIzHulpvragen() as $hulpvraag) {
            if ($hulpvraag->isGekoppeld() && !$hulpvraag->isAfgesloten()) {
                $projecten[] = $hulpvraag->getProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getProjectenAfgeslotenKoppelingen(IzKlant $izKlant)
    {
        $projecten = [];

        foreach ($izKlant->getIzHulpvragen() as $hulpvraag) {
            if ($hulpvraag->isGekoppeld() && $hulpvraag->isAfgesloten()) {
                $projecten[] = $hulpvraag->getProject();
            }
        }

        return implode(', ', array_unique($projecten));
    }

    public function getStartdatumEersteKoppeling(IzKlant $izKlant)
    {
        $data = [];

        foreach ($izKlant->getIzHulpvragen() as $hulpvraag) {
            if ($hulpvraag->isGekoppeld()) {
                $data[] = $hulpvraag->getKoppelingStartdatum();
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

    public function getDatumLaatsteAfgeslotenKoppeling(IzKlant $izKlant)
    {
        $data = [];

        foreach ($izKlant->getIzHulpvragen() as $hulpvraag) {
            if ($hulpvraag->isGekoppeld() && $hulpvraag->isAfgesloten()) {
                $data[] = $hulpvraag->getKoppelingEinddatum();
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

    public function getMedewerkers(IzKlant $izKlant)
    {
        $medewerkers = [];

        foreach ($izKlant->getIzHulpvragen() as $hulpvraag) {
            if ($hulpvraag->getMedewerker()) {
                $medewerkers[] = $hulpvraag->getMedewerker();
            }
        }

        return implode(', ', array_unique($medewerkers));
    }
}
