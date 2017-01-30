<?php

namespace IzBundle\Report;

use AppBundle\Report\Table;

class VrijwilligersPerProjectNamenlijst extends AbstractVrijwilligersNamenlijstReport
{
    protected $columns = [
        'Nummer' => 'id',
        'Naam' => 'naam',
        'Project' => 'project',
    ];

    protected $title = 'Vrijwilligers per project (namenlijst)';

    protected $xDescription = 'Vrijwilligers met intake en hulpaanbod (op basis van intakedatum en afsluitdatum)';

    protected function init()
    {
        $this->beginstand = $this->repository->selectByProject('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->selectByProject('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->selectByProject('afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->selectByProject('eindstand', $this->startDate, $this->endDate);
    }
}
