<?php

namespace GaBundle\Report;

use AppBundle\Report\Grid;

class VrijwilligersPerGroep extends AbstractReport
{
    protected $title = 'Vrijwilligers per groep';

    protected $yDescription = 'groepnaam';

    protected function init()
    {
        $data = [];
        foreach ($this->repositories as $group => $repository) {
            $title = $repository->getRepositoryTitle();
            $data[$title] = $repository->countVrijwilligersPerGroep($this->startDate, $this->endDate);
        }
        $this->data = $data;
    }

    protected function build()
    {
        $columns = [
            'Aantal activiteiten' => 'aantal_activiteiten',
            'Aantal vrijwilligers' => 'aantal_vrijwilligers',
            'Aantal vrijwilligersdeelnames' => 'aantal_vrijwilligersdeelnames',
        ];

        foreach ($this->data as $title => $data) {
            $grid = new Grid($data, $columns, 'groepnaam');
            $grid
                ->setStartDate($this->startDate)
                ->setEndDate($this->endDate)
                ->setYNullReplacement('Onbekend')
            ;

            $this->reports[] = [
                'title' => $title,
                'yDescription' => 'Groep',
                'data' => $grid->render(),
            ];
        }
    }
}
