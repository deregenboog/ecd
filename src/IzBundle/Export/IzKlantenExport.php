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

    public function getStartdatumEersteKoppeling(IzKlant $izKlant)
    {
        $data = [];

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            if ($izHulpvraag->isGekoppeld()) {
                $data[] = $izHulpvraag->getKoppelingStartdatum();
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

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            if ($izHulpvraag->isGekoppeld() && $izHulpvraag->isAfgesloten()) {
                $data[] = $izHulpvraag->getKoppelingEinddatum();
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

        foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
            if ($izHulpvraag->getMedewerker()) {
                $medewerkers[] = $izHulpvraag->getMedewerker();
            }
        }

        return implode(', ', array_unique($medewerkers));
    }
}
