<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use MwBundle\Report\AbstractMwReport;
use MwBundle\Service\KlantDao;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\LocatieDao;
use MwBundle\Service\VerslagDao;

class Amstelland extends  AbstractMwReport
{

    protected $title = 'Amstelland';

    protected function filterLocations($allLocations)
    {
        /**
         * Filter: alleen 'zonder zorg'
         */
        foreach($allLocations as $k=> $locatie)
        {
            $naam = $locatie->getNaam();
            if(strpos($naam, "Amstelland") !== false
            ) {
                $this->locaties[] = $locatie->getNaam();
            }

        }
    }

}
