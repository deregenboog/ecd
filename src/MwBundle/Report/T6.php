<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use MwBundle\Report\AbstractMwReport;
use MwBundle\Service\KlantDao;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\LocatieDao;
use MwBundle\Service\VerslagDao;

class T6 extends  AbstractMwReport
{

    protected $title = 'T6';

    protected function filterLocations($allLocations)
    {
        /**
         * Filter: alleen 'zonder zorg'
         */
        foreach($allLocations as $k=> $locatie)
        {
            $naam = $locatie->getNaam();
            if(strpos($naam, "T6 ") === false
//                || strpos($naam,"T6") !== false
//                || strpos($naam,"STED") !== false
                || strpos($naam,"Wachtlijst") !== false
            ) {
                //skip locatie
                continue;
            }
            $this->locaties[] = $locatie->getNaam();
        }
    }

}
