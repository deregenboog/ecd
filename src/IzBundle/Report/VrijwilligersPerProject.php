<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;

class VrijwilligersPerProject extends AbstractVrijwilligersReport
{
    protected $title = 'Vrijwilligers per project';

    protected $yPath = 'project';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal vrijwilligers met intake en hulpaanbod (op basis van intakedatum en afsluitdatum)';

    protected $yDescription = 'Project';

    protected function init()
    {
        $this->beginstand = $this->repository->countByProject('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countByProject('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countByProject('afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countByProject('eindstand', $this->startDate, $this->endDate);
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
