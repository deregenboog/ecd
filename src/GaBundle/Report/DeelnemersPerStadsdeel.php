<?php

namespace GaBundle\Report;

use AppBundle\Report\Grid;

class DeelnemersPerStadsdeel extends AbstractReport
{
    protected $title = 'Deelnemers per stadsdeel';

    protected function init()
    {
        $data = [];
        foreach ($this->repositories as $group => $repository) {
            $data[$group] = $repository->countDeelnemersPerStadsdeel($this->startDate, $this->endDate);
        }
        $this->data = $data;
    }

    protected function build()
    {
        $columns = [
            'Aantal activiteiten' => 'aantal_activiteiten',
            'Aantal deelnemers' => 'aantal_deelnemers',
            'Aantal deelnames' => 'aantal_deelnames',
            'Aantal anonieme deelnames' => 'aantal_anonieme_deelnames',
        ];

        foreach ($this->data as $title => $data) {
            $grid = new Grid($data, $columns, 'stadsdeel');
            $grid
                ->setStartDate($this->startDate)
                ->setEndDate($this->endDate)
                ->setYNullReplacement('Onbekend')
                ->setYTotals(false)
            ;

            $this->reports[] = [
                'title' => $title,
                'xDescription' => 'LET OP: Stadsdeel is op basis van woonadres deelnemer (dus niet op basis van activiteitlocatie)',
                'yDescription' => 'Stadsdeel',
                'data' => $grid->render(),
            ];
        }
    }
}
