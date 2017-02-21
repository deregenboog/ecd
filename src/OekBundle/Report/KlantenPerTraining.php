<?php

namespace OekBundle\Report;

use AppBundle\Report\Table;

class KlantenPerTraining extends AbstractKlantenReport
{
    protected $title = 'Deelnemers per training';

    protected $yPath = 'training';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal deelnemers';

    protected $yDescription = 'Training';

    protected function init()
    {
        $this->beginstand = $this->repository->countByTraining('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countByTraining('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countByTraining('afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countByTraining('eindstand', $this->startDate, $this->endDate);
    }

    protected function build()
    {
        $beginstandTable = new Table($this->beginstand, $this->xPath, $this->yPath, $this->nPath);
        $beginstandTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setYTotals(false);

        $gestartTable = new Table($this->gestart, $this->xPath, $this->yPath, $this->nPath);
        $gestartTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setYTotals(false);

        $afgeslotenTable = new Table($this->afgesloten, $this->xPath, $this->yPath, $this->nPath);
        $afgeslotenTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setYTotals(false);

        $eindstandTable = new Table($this->eindstand, $this->xPath, $this->yPath, $this->nPath);
        $eindstandTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setYTotals(false);

        $this->reports = [
            [
                'title' => 'Beginstand',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $beginstandTable->render(),
            ],
            [
                'title' => 'Gestart',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $gestartTable->render(),
            ],
            [
                'title' => 'Afgesloten',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $afgeslotenTable->render(),
            ],
            [
                'title' => 'Eindstand',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $eindstandTable->render(),
            ],
        ];
    }
}
