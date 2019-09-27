<?php

namespace GaBundle\Report;

use AppBundle\Report\Grid;

class VrijwilligersTotaal extends AbstractReport
{
    protected $title = 'Vrijwilligers totaal';

    protected function init()
    {
        $data = [];
        foreach ($this->repositories as $group => $repository) {
            $data[] = array_merge(
                ['group' => $group],
                $repository->countVrijwilligers($this->startDate, $this->endDate)
            );
        }

        $this->data = $data;
    }

    protected function build()
    {
        $columns = [
            'Aantal vrijwilligers' => 'aantal_vrijwilligersdeelnames',
            'Aantal nieuwe vrijwilligers' => 'aantal_nieuwe_vrijwilligers',
            'Aantal uitstroom/doorstroom' => 'aantal_gestopte_vrijwilligers',
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
