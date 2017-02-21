<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;

class VrijwilligersPerProjectStadsdeel extends AbstractVrijwilligersReport
{
    protected $title = 'Vrijwilligers per project en stadsdeel';

    protected $xPath = 'project';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Project';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $this->beginstand = $this->repository->countByProjectAndStadsdeel('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countByProjectAndStadsdeel('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countByProjectAndStadsdeel('afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countByProjectAndStadsdeel('eindstand', $this->startDate, $this->endDate);
    }

    protected function build()
    {
        $beginstandTable = new Table($this->beginstand, $this->xPath, $this->yPath, $this->nPath);
        $beginstandTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setXTotals(false);

        $gestartTable = new Table($this->gestart, $this->xPath, $this->yPath, $this->nPath);
        $gestartTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setXTotals(false);

        $afgeslotenTable = new Table($this->afgesloten, $this->xPath, $this->yPath, $this->nPath);
        $afgeslotenTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setXTotals(false);

        $eindstandTable = new Table($this->eindstand, $this->xPath, $this->yPath, $this->nPath);
        $eindstandTable->setStartDate($this->startDate)->setEndDate($this->endDate)->setXTotals(false);

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
