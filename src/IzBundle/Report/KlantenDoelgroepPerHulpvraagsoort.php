<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;

class KlantenDoelgroepPerHulpvraagsoort extends AbstractKoppelingenReport
{
    protected $title = 'Aantal deelnemers per doelgroep per hulpvraagsoort';

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

        //gestart
        $gestart = new Table($this->doelgroepen, $this->xPath, $this->yPath, $this->nPath);
        $gestart->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports = [
            [
                'title' => 'Gestartte koppelingen',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $gestart->render(),
            ],
        ];
    }
}
