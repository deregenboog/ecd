<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;
use IzBundle\Repository\IzKlantRepository;

class KlantenPerProject extends AbstractKlantenReport
{
    protected $title = 'Deelnemers per project';

    protected $yPath = 'projectnaam';

    protected $nPath = 'aantal';

    protected $yDescription = 'Project';

    protected function init()
    {
        $this->beginstand = $this->repository->countByProject(
            IzKlantRepository::REPORT_BEGINSTAND,
            $this->startDate,
            $this->endDate
        );
        $this->gestart = $this->repository->countByProject(
            IzKlantRepository::REPORT_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->nieuwGestart = $this->repository->countByProject(
            IzKlantRepository::REPORT_NIEUW_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->afgesloten = $this->repository->countByProject(
            IzKlantRepository::REPORT_AFGESLOTEN,
            $this->startDate,
            $this->endDate
        );
        $this->eindstand = $this->repository->countByProject(
            IzKlantRepository::REPORT_EINDSTAND,
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
                'xDescription' => 'Aantal deelnemers met een lopende koppeling op startdatum.',
                'yDescription' => $this->yDescription,
                'data' => $beginstandTable->render(),
            ],
            [
                'title' => 'Gestart',
                'xDescription' => 'Aantal deelnemers dat binnen de periode een koppeling startte en op startdatum geen lopende koppeling had.',
                'yDescription' => $this->yDescription,
                'data' => $gestartTable->render(),
            ],
            [
                'title' => 'Nieuw gestart',
                'xDescription' => 'Aantal deelnemers dat binnen de periode voor het eerst een koppeling startte.',
                'yDescription' => $this->yDescription,
                'data' => $nieuwGestartTable->render(),
            ],
            [
                'title' => 'Afgesloten',
                'xDescription' => 'Aantal deelnemers dat binnen de periode een koppeling afsloot en op einddatum geen lopende koppeling had.',
                'yDescription' => $this->yDescription,
                'data' => $afgeslotenTable->render(),
            ],
            [
                'title' => 'Eindstand',
                'xDescription' => 'Aantal deelnemers met een lopende koppeling op einddatum.',
                'yDescription' => $this->yDescription,
                'data' => $eindstandTable->render(),
            ],
        ];
    }
}
