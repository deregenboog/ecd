<?php

namespace GaBundle\Report;

use AppBundle\Report\Table;

class DeelnemersPerGroepStadsdeel extends AbstractReport
{
    protected $title = 'Deelnemers per groep en stadsdeel';

    protected $xDescription = 'LET OP: Stadsdeel is op basis van woonadres deelnemer (dus niet op basis van activiteitlocatie)';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $data = [];
        foreach ($this->repositories as $group => $repository) {
            $title = $repository->getRepositoryTitle();
            $data[$title] = $repository->countDeelnemersPerGroepStadsdeel($this->startDate, $this->endDate);
        }
        $this->data = $data;
    }

    protected function build()
    {
        foreach ($this->data as $title => $data) {
            $report = new Table($data, 'groepnaam', 'stadsdeel', 'aantal_deelnemers');
            $report
                ->setStartDate($this->startDate)
                ->setEndDate($this->endDate)
                ->setYNullReplacement('Onbekend')
                ->setYTotals(false)
            ;
            $this->reports[] = [
                'title' => $title.' - Deelnemers',
                'description' => 'Aantal deelnemers, exclusief anonieme deelnames',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $report->render(),
            ];

            $report = new Table($data, 'groepnaam', 'stadsdeel', 'aantal_deelnames');
            $report
                ->setStartDate($this->startDate)
                ->setEndDate($this->endDate)
                ->setYNullReplacement('Onbekend')
                ->setYTotals(false)
            ;
            $this->reports[] = [
                'title' => $title.' - Deelnames',
                'description' => 'Aantal deelnames, exclusief anonieme deelnames',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $report->render(),
            ];
        }
    }
}
