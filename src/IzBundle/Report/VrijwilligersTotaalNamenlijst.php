<?php

namespace IzBundle\Report;

use AppBundle\Report\Grid;
use AppBundle\Report\Listing;

class VrijwilligersTotaalNamenlijst extends AbstractVrijwilligersNamenlijstReport
{
    protected $title = 'Vrijwilligers totaal (namenlijst)';

    protected $xDescription = 'Vrijwilligers met intake en hulpaanbod (op basis van intakedatum en afsluitdatum)';

    protected function init()
    {
        $this->beginstand = $this->repository->select('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->select('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->select('afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->select('eindstand', $this->startDate, $this->endDate);
    }
}
