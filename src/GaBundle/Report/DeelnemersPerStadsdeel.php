<?php

namespace GaBundle\Report;

use AppBundle\Report\Grid;

class DeelnemersPerStadsdeel extends AbstractReport
{
    protected function init()
    {
        $this->data = $this->repository->countDeelnemersPerStadsdeel($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $columns = [
            'Aantal activiteiten' => 'aantal_activiteiten',
            'Aantal deelnemers' => 'aantal_deelnemers',
            'Aantal unieke deelnemers' => 'aantal_unieke_deelnemers',
        ];
        $grid = new Grid($this->data, $columns, 'stadsdeel');
        $grid
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYNullReplacement('Onbekend')
            ->setYTotals(false)
        ;

        $this->reports = [
            [
                'title' => '',
                'xDescription' => 'LET OP: Stadsdeel is op basis van woonadres deelnemer (dus niet op basis van activiteitlocatie)',
                'yDescription' => 'Stadsdeel',
                'data' => $grid->render(),
            ],
        ];
    }
}
