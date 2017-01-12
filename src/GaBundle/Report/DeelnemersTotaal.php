<?php

namespace GaBundle\Report;

use AppBundle\Report\Grid;

class DeelnemersTotaal extends AbstractReport
{
    protected function init()
    {
        $this->data = $this->repository->countDeelnemers($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $columns = [
            'Aantal activiteiten' => 'aantal_activiteiten',
            'Aantal deelnemers' => 'aantal_deelnemers',
            'Aantal unieke deelnemers' => 'aantal_unieke_deelnemers',
        ];
        $grid = new Grid($this->data, $columns);
        $grid
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYNullReplacement('Totaal')
        ;

        $this->reports = [
            [
                'title' => '',
                'data' => $grid->render(),
            ],
        ];
    }
}
