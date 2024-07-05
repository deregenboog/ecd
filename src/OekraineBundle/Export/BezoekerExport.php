<?php

namespace OekraineBundle\Export;

use AppBundle\Entity\Klant;
use AppBundle\Export\ExportInterface;
use AppBundle\Export\GenericExport;

class BezoekerExport extends GenericExport implements ExportInterface
{
    //    public function getInkomens(Klant $klant)
    //    {
    //        $inkomens = [];
    //
    //        if ($klant->getLaatsteIntake()) {
    //            foreach ($klant->getLaatsteIntake()->getInkomens() as $inkomen) {
    //                $inkomens[] = (string) $inkomen;
    //            }
    //            if ($klant->getLaatsteIntake()->getInkomenOverig()) {
    //                $inkomens[] = $klant->getLaatsteIntake()->getInkomenOverig();
    //            }
    //        }
    //
    //        return implode(', ', array_unique($inkomens));
    //    }
}
