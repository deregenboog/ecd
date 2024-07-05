<?php

namespace MwBundle\Report;

class T6 extends AbstractMwReport
{
    protected $title = 'T6';

    protected function filterLocations($allLocations)
    {
        /*
         * Filter: alleen 'zonder zorg'
         */
        foreach ($allLocations as $k => $locatie) {
            $naam = $locatie->getNaam();
            if (false !== strpos($naam, 'T6 ')
                || false !== strpos($naam, 'Wachtlijst T6')
            ) {
                $this->locaties[] = $locatie->getNaam();
            }
        }
    }
}
