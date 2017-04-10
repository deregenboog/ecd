<?php

namespace IzBundle\Report;

class VrijwilligersTotaal extends AbstractVrijwilligersReport
{
    protected $title = 'Vrijwilligers totaal';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal vrijwilligers met intake en hulpaanbod (op basis van intakedatum en afsluitdatum)';

    protected function init()
    {
        $this->beginstand = $this->repository->count('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->count('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->count('afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->count('eindstand', $this->startDate, $this->endDate);
    }
}
