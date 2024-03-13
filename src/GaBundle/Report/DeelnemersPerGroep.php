<?php

namespace GaBundle\Report;

use AppBundle\Report\Grid;

class DeelnemersPerGroep extends AbstractReport
{
    protected $title = 'Deelnemers per groep';

    protected function init()
    {
        $data = [];
        foreach ($this->repositories as $group => $repository) {

            $title = $repository->getRepositoryTitle();
            $data[$title] = $repository->countDeelnemersPerGroep($this->startDate, $this->endDate);
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
