<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use MwBundle\Service\KlantDao;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\LocatieDao;
use MwBundle\Service\MwDossierStatusDao;
use MwBundle\Service\VerslagDao;

class AMW extends AbstractMwReport
{

    protected $title = 'AMW';



    protected function filterLocations($allLocations)
    {
        /**
         * Filter: alles STED, alles Wchtlijst, alles Zonder Zorg, T6.
         */
        foreach($allLocations as $k=> $locatie)
        {
            $naam = $locatie->getNaam();
            if(strpos($naam, "Zonder ") !== false
                || strpos($naam,"Dobre ") !== false
                || strpos($naam,"Begeleid ") !== false
                || strpos($naam,"T6") !== false
                || strpos($naam,"STED") !== false
                || strpos($naam,"Wachtlijst") !== false
                || strpos($naam,"Amstelland") !== false
            ) {
                //skip locatie
                continue;
            }
            $this->locaties[] = $locatie->getNaam();
        }
    }

}
