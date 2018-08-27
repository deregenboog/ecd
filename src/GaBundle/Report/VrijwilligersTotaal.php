<?php

namespace GaBundle\Report;

use AppBundle\Report\Grid;

class VrijwilligersTotaal extends AbstractReport
{
    protected $title = 'Vrijwilligers totaal';

    protected function init()
    {
        $this->data = $this->repository->countVrijwilligers($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $columns = [
            'Aantal unieke vrijwilligers' => 'aantal_vrijwilligers',
            'Nieuw' => 'aantal_nieuwe_vrijwilligers',
            'Uitstroom/doorstroom' => 'aantal_gestopte_vrijwilligers',
        ];

        $grid = new Grid($this->data, $columns);
        $grid
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYNullReplacement('Totaal')
        ;

        $this->reports[] = [
            'title' => '',
            'data' => $grid->render(),
        ];
    }
}
