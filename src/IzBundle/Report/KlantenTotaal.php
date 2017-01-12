<?php

namespace IzBundle\Report;

class KlantenTotaal extends AbstractKlantenReport
{
    protected $title = 'Deelnemers totaal';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal deelnemers met koppeling (op basis van start- en einddatum van koppeling).';

    protected function init()
    {
        $this->beginstand = $this->repository->count('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->count('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->count('afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->count('eindstand', $this->startDate, $this->endDate);
    }
}
