<?php

namespace MwBundle\Report;

use InloopBundle\Entity\Locatie;

class AMW extends AbstractMwReport
{
    protected $title = 'AMW';

    protected function filterLocations($allLocations)
    {
        /*
         * Filter: alles STED, alles Wchtlijst, alles Zonder Zorg, T6.
         */
        foreach ($allLocations as $k => $locatie) {
            $naam = $locatie->getNaam();
            if($naam === null) $naam = "";
            if (false !== strpos($naam, 'Zonder ')
                || false !== strpos($naam, 'Dobre ')
                || false !== strpos($naam, 'Begeleid ')
                || false !== strpos($naam, 'T6')
                || false !== strpos($naam, 'STED')
                || false !== strpos($naam, 'Wachtlijst')
                || false !== strpos($naam, 'Amstelland')
            ) {
                // skip locatie
                continue;
            }
            $this->locaties[] = $locatie->getNaam();
        }
    }
}
