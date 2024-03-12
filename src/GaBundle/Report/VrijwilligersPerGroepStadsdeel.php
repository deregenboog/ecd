<?php

namespace GaBundle\Report;

use AppBundle\Report\Table;

class VrijwilligersPerGroepStadsdeel extends AbstractReport
{
    protected $title = 'Vrijwilligers per groep en stadsdeel';

    protected $xDescription = 'LET OP: Stadsdeel is op basis van woonadres vrijwilliger (dus niet op basis van activiteitlocatie)';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $data = [];
        foreach ($this->repositories as $group => $repository) {
            $title = $repository->getRepositoryTitle();
            $data[$title] = $repository->countVrijwilligersPerGroepStadsdeel($this->startDate, $this->endDate);
        }
        $this->data = $data;
    }

    protected function build()
    {
        foreach ($this->data as $title => $data) {
            $report = new Table($data, 'groepnaam', 'stadsdeel', 'aantal_vrijwilligers');
            $report
                ->setStartDate($this->startDate)
                ->setEndDate($this->endDate)
                ->setYNullReplacement('Onbekend')
                ->setYTotals(false)
            ;
            $this->reports[] = [
                'title' => $title.' - Unieke vrijwilligers',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $report->render(),
            ];

            $report = new Table($data, 'groepnaam', 'stadsdeel', 'aantal_vrijwilligersdeelnames');
            $report
                ->setStartDate($this->startDate)
                ->setEndDate($this->endDate)
                ->setYNullReplacement('Onbekend')
                ->setYTotals(false)
            ;
            $this->reports[] = [
                'title' => $title.' - Vrijwilligers',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $report->render(),
            ];
        }
    }
}
