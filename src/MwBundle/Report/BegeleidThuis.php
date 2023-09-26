<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use MwBundle\Service\KlantDao;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\LocatieDao;
use MwBundle\Service\VerslagDao;

class BegeleidThuis extends  AbstractMwReport
{

    protected $title = 'Begeleid thuis';

    protected function filterLocations($allLocations)
    {
        /**
         * Filter: alleen 'zonder zorg'
         */
        foreach($allLocations as $k=> $locatie)
        {
            $naam = $locatie->getNaam();
            if(strpos($naam, "Zonder ") === false
//                || strpos($naam,"T6") !== false
//                || strpos($naam,"STED") !== false
//                || strpos($naam,"Wachtlijst") !== false
            ) {
                //skip locatie
                continue;
            }
            $this->locaties[] = $locatie->getNaam();
        }
    }

}
