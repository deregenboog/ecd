<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;

class KlantenDoelgroepPerHulpvraagsoort extends AbstractKoppelingenReport
{
    protected $title = 'Deelnemers per doelgroep per hulpvraagsoort';

    protected $xPath = 'doelgroepnaam';

    protected $yPath = 'hulpvraagsoortnaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Doelgroep';

    protected $yDescription = 'Hulpvraagsoort';

    protected function init()
    {
        $this->doelgroepen = $this->repository->countDoelgroepenByHulpvraagsoort($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $afgeslotenTable = new Table($this->doelgroepen, $this->xPath, $this->yPath, $this->nPath);
        $afgeslotenTable->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports = [
            [
                'title' => 'Doelgroepen',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $afgeslotenTable->render(),
            ],
        ];
    }
}
