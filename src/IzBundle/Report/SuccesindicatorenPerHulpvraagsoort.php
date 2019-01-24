<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;

class SuccesindicatorenPerHulpvraagsoort extends AbstractKoppelingenReport
{
    protected $title = 'Succesindicatoren per hulpvraagsoort';

    protected $xPath = 'hulpvraagsoortnaam';

    protected $yPath = 'succesindicatornaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Hulpvraagsoort';

    protected $yDescription = 'Succesindicator';

    protected function init()
    {
        $this->afgesloten = $this->repository->countSuccesindicatorenByHulpvraagsoort($this->startDate, $this->endDate);
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
