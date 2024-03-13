<?php

namespace Tests\GaBundle\MockObjects;

class GroepBRepository
{

    public function getRepositoryTitle(){
        return "Groep B";
    }
    public function countDeelnemersPerGroepStadsdeel($startDate, $endDate) {
        // this is just an example, you can handle it as per your logic
        return [
            [
                'groepnaam' => 'Groep B',
                'stadsdeel' => 'Stadsdeel B',
                'aantal_deelnemers' => 21,
                'aantal_deelnames' => 211,
            ],
            [
                'groepnaam' => 'Groep B',
                'stadsdeel' => 'Stadsdeel C',
                'aantal_deelnemers' => 32,
                'aantal_deelnames' => 322,
            ],
        ];
    }
}