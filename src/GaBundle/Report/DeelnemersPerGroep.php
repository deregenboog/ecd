<?php

namespace GaBundle\Report;

use AppBundle\Report\Grid;

class DeelnemersPerGroep extends AbstractReport
{
    protected $title = 'Deelnemers per groep';

    protected function init()
    {
        $this->data = $this->repository->countDeelnemersPerGroep($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $columns = [
            'Aantal activiteiten' => 'aantal_activiteiten',
            'Aantal deelnemers' => 'aantal_deelnemers',
            'Aantal unieke deelnemers' => 'aantal_unieke_deelnemers',
        ];
        $grid = new Grid($this->data, $columns, 'groep');
        $grid
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYNullReplacement('Onbekend')
        ;

        $this->reports[] = [
            'title' => '',
            'yDescription' => 'Groep',
            'data' => $grid->render(),
        ];
    }
}
