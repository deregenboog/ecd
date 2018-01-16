<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;
use IzBundle\Repository\IzVrijwilligerRepository;

class VrijwilligersPerProject extends AbstractVrijwilligersReport
{
    protected $title = 'Vrijwilligers per project';

    protected $yPath = 'project';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal vrijwilligers met intake en hulpaanbod (op basis van intakedatum en afsluitdatum)';

    protected $yDescription = 'Project';

    protected function init()
    {
        $this->beginstand = $this->repository->countByProject(
            IzVrijwilligerRepository::REPORT_BEGINSTAND,
            $this->startDate,
            $this->endDate
        );
        $this->gestart = $this->repository->countByProject(
            IzVrijwilligerRepository::REPORT_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->nieuwGestart = $this->repository->countByProject(
            IzVrijwilligerRepository::REPORT_NIEUW_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->afgesloten = $this->repository->countByProject(
            IzVrijwilligerRepository::REPORT_AFGESLOTEN,
            $this->startDate,
            $this->endDate
        );
        $this->eindstand = $this->repository->countByProject(
            IzVrijwilligerRepository::REPORT_EINDSTAND,
            $this->startDate,
            $this->endDate
        );
    }

    protected function build()
    {
        $beginstandTable = new Table($this->beginstand, $this->xPath, $this->yPath, $this->nPath);
        $beginstandTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setYTotals(false);

        $gestartTable = new Table($this->gestart, $this->xPath, $this->yPath, $this->nPath);
        $gestartTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setYTotals(false);

        $nieuwGestartTable = new Table($this->nieuwGestart, $this->xPath, $this->yPath, $this->nPath);
        $nieuwGestartTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setYTotals(false);

        $afgeslotenTable = new Table($this->afgesloten, $this->xPath, $this->yPath, $this->nPath);
        $afgeslotenTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setYTotals(false);

        $eindstandTable = new Table($this->eindstand, $this->xPath, $this->yPath, $this->nPath);
        $eindstandTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setYTotals(false);

        $this->reports = [
            [
                'title' => 'Beginstand',
                'xDescription' => 'Aantal vrijwilligers met een lopende koppeling op startdatum.',
                'yDescription' => $this->yDescription,
                'data' => $beginstandTable->render(),
            ],
            [
                'title' => 'Gestart',
                'xDescription' => 'Aantal vrijwilligers dat binnen de periode een koppeling startte en op startdatum geen lopende koppeling had.',
                'yDescription' => $this->yDescription,
                'data' => $gestartTable->render(),
            ],
            [
                'title' => 'Nieuw gestart',
                'xDescription' => 'Aantal vrijwilligers dat binnen de periode voor het eerst een koppeling startte.',
                'yDescription' => $this->yDescription,
                'data' => $nieuwGestartTable->render(),
            ],
            [
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal vrijwilligers dat binnen de periode een koppeling afsloot en op einddatum geen lopende koppeling had.',
                'yDescription' => $this->yDescription,
                'data' => $afgeslotenTable->render(),
            ],
            [
                'title' => 'Eindstand',
                'xDescription' => 'Aantal vrijwilligers met een lopende koppeling op einddatum.',
                'yDescription' => $this->yDescription,
                'data' => $eindstandTable->render(),
            ],
        ];
    }
}
