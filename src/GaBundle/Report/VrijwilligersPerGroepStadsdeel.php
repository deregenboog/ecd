<?php

namespace GaBundle\Report;

use AppBundle\Report\Table;

class VrijwilligersPerGroepStadsdeel extends AbstractReport
{
    protected $xDescription = 'LET OP: Stadsdeel is op basis van woonadres vrijwilliger (dus niet op basis van activiteitlocatie)';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $this->data = $this->repository->countVrijwilligersPerGroepStadsdeel($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $report = new Table($this->data, 'groep', 'stadsdeel', 'aantal_unieke_vrijwilligers');
        $report
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYNullReplacement('Onbekend')
            ->setYTotals(false)
        ;
        $this->reports[] = [
            'title' => 'Unieke vrijwilligers',
            'xDescription' => $this->xDescription,
            'yDescription' => $this->yDescription,
            'data' => $report->render(),
        ];

        $report = new Table($this->data, 'groep', 'stadsdeel', 'aantal_vrijwilligers');
        $report
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYNullReplacement('Onbekend')
            ->setYTotals(false)
        ;
        $this->reports[] = [
            'title' => 'Vrijwilligers',
            'xDescription' => $this->xDescription,
            'yDescription' => $this->yDescription,
            'data' => $report->render(),
        ];
    }
}
