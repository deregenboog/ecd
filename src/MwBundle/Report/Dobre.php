<?php

namespace MwBundle\Report;

use InloopBundle\Entity\Locatie;

class Dobre extends AbstractMwReport
{
    protected $title = 'Dobre 020';

    protected function filterLocations($allLocations)
    {
        /*
         * Filter: alleen 'zonder zorg'
         */
        foreach ($allLocations as $k => $locatie) {
            $naam = $locatie->getNaam();
            if (false === strpos($naam, 'Dobre ')
            ) {
                // skip locatie
                continue;
            }
            $this->locaties[] = $locatie->getNaam();
        }
    }
}
