<?php

namespace InloopBundle\Export;

use AppBundle\Export\GenericExport;
use AppBundle\Entity\Klant;

class RegistratiesExport extends GenericExport
{
    public function getInkomens(Klant $klant)
    {
        $inkomens = [];

        if ($klant->getLaatsteIntake()) {
            foreach ($klant->getLaatsteIntake()->getInkomens() as $inkomen) {
                $inkomens[] = (string) $inkomen;
            }
            if ($klant->getLaatsteIntake()->getInkomenOverig()) {
                $inkomens[] = $klant->getLaatsteIntake()->getInkomenOverig();
            }
        }

        return implode(', ', array_unique($inkomens));
    }
}
