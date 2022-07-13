<?php

namespace DagbestedingBundle\Export;

use AppBundle\Export\GenericExport;
use DagbestedingBundle\Entity\Traject;

class TrajectenExport extends GenericExport
{
    public function getRapportageData(Traject $traject)
    {
        $data = [];

        foreach ($traject->getRapportages() as $rapportage) {
            $data[] = $rapportage->getDatum()->format('d-m-Y');
        }

        return implode(', ', $data);
    }

    public function getProjecten(Traject $traject)
    {
        $data = [];

        foreach ($traject->getDeelnames() as $d) {
            $data[] = $d->getProject()->getNaam();
        }

        return implode(', ', $data);
    }

    public function getLocaties(Traject $traject)
    {
        $data = [];

        foreach ($traject->getLocaties() as $l) {
            $data[] = $l->getNaam();
        }

        return implode(', ', $data);
    }
}
