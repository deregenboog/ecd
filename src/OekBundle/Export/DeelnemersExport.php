<?php

namespace OekBundle\Export;

use OekBundle\Entity\Deelnemer;
use AppBundle\Export\GenericExport;

class DeelnemersExport extends GenericExport
{
    public function getGroepen(Deelnemer $deelnemer)
    {
        $groepen = [];

        foreach ($deelnemer->getGroepen() as $groep) {
            $groepen[] = (string) $groep;
        }

        return implode(', ', array_unique($groepen));
    }

    public function getTrainingen(Deelnemer $deelnemer)
    {
        $trainingen = [];

        foreach ($deelnemer->getDeelnames() as $deelname) {
            $trainingen[] = (string) $deelname->getTraining();
        }

        return implode(', ', array_unique($trainingen));
    }
}
