<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;
use IzBundle\Repository\IzKlantRepository;

abstract class AbstractKlantenReport extends AbstractReport
{
    protected $succesvolAfgesloten;

    public function __construct(IzKlantRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function build()
    {
        $beginstandTable = new Table($this->beginstand, $this->xPath, $this->yPath, $this->nPath);
        $beginstandTable->setStartDate($this->startDate)->setEndDate($this->endDate);

        $gestartTable = new Table($this->gestart, $this->xPath, $this->yPath, $this->nPath);
        $gestartTable->setStartDate($this->startDate)->setEndDate($this->endDate);

        $nieuwGestartTable = new Table($this->nieuwGestart, $this->xPath, $this->yPath, $this->nPath);
        $nieuwGestartTable->setStartDate($this->startDate)->setEndDate($this->endDate);

        $afgeslotenTable = new Table($this->afgesloten, $this->xPath, $this->yPath, $this->nPath);
        $afgeslotenTable->setStartDate($this->startDate)->setEndDate($this->endDate);

        $eindstandTable = new Table($this->eindstand, $this->xPath, $this->yPath, $this->nPath);
        $eindstandTable->setStartDate($this->startDate)->setEndDate($this->endDate);

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
