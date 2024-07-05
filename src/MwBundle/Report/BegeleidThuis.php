<?php

namespace MwBundle\Report;

use InloopBundle\Entity\Locatie;

class BegeleidThuis extends AbstractMwReport
{
    protected $title = 'Begeleid thuis';

    protected function filterLocations($allLocations)
    {
        /*
         * Filter: alleen 'zonder zorg'
         */
        foreach ($allLocations as $k => $locatie) {
            $naam = $locatie->getNaam();
            if (false === strpos($naam, 'Zonder ')
                && false === strpos($naam, 'Vriendenhuis')
                && false === strpos($naam, 'Omslag')
                && false === strpos($naam, 'Begeleid ')
            ) {
                // skip locatie
                continue;
            }
            $this->locaties[] = $locatie->getNaam();
        }
    }
}
