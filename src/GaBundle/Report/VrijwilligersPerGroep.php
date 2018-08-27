<?php

namespace GaBundle\Report;

use AppBundle\Report\Grid;

class VrijwilligersPerGroep extends AbstractReport
{
    protected $title = 'Vrijwilligers per groep';

    protected $yDescription = 'Groep';

    protected function init()
    {
        $this->data = $this->repository->countVrijwilligersPerGroep($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $columns = [
            'Aantal activiteiten' => 'aantal_activiteiten',
            'Aantal vrijwilligers' => 'aantal_vrijwilligers',
            'Aantal unieke vrijwilligers' => 'aantal_unieke_vrijwilligers',
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
