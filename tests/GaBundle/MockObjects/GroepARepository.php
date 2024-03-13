<?php

namespace Tests\GaBundle\MockObjects;

class GroepARepository
{

    public function getRepositoryTitle(){
        return "Groep A";
    }

    public function countDeelnemersPerGroepStadsdeel($startDate, $endDate) {
        // this is just an example, you can handle it as per your logic
        return [
            [
                'groepnaam' => 'Groep A',
                'stadsdeel' => 'Stadsdeel A',
                'aantal_deelnemers' => 43,
                'aantal_deelnames' => 433,
            ],
            [
                'groepnaam' => 'Groep A',
                'stadsdeel' => 'Stadsdeel B',
                'aantal_deelnemers' => 21,
                'aantal_deelnames' => 211,
            ],
        ];
    }
}