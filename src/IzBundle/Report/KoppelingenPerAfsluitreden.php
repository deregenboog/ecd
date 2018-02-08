<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;

class KoppelingenPerAfsluitreden extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per afsluitreden';

    protected $yPath = 'afsluitreden';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal koppelingen';

    protected $yDescription = 'Afsluitreden';

    protected function init()
    {
        $this->afgesloten = $this->repository->countKoppelingenByAfsluitreden('afgesloten', $this->startDate, $this->endDate);
    }

    protected function build()
    {
        $afgeslotenTable = new Table($this->afgesloten, $this->xPath, $this->yPath, $this->nPath);
        $afgeslotenTable->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports = [
            [
                'title' => 'Afgesloten',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $afgeslotenTable->render(),
            ],
        ];
    }
}
