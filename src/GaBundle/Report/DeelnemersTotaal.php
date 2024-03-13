<?php

namespace GaBundle\Report;

use AppBundle\Report\Grid;

class DeelnemersTotaal extends AbstractReport
{
    protected $title = 'Deelnemers totaal';

    protected function init()
    {
        $data = [];
        foreach ($this->groupTypes->getTypeNames() as $title) {
            $repository = $this->groupTypes->getType($title);
            $data[] = array_merge(
                ['group' => $title],
                $repository->countDeelnemers($this->startDate, $this->endDate)
            );
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

        $grid = new Grid($this->data, $columns, 'group');
        $grid
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYTotals(false)
        ;
        $this->reports = [[
            'title' => '',
            'data' => $grid->render(),
        ]];
    }
}
