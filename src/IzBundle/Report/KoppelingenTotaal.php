<?php

namespace IzBundle\Report;

class KoppelingenTotaal extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen totaal';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal koppelingen';

    protected function init()
    {
        $this->beginstand = $this->repository->count('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->count('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->count('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->count('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->count('eindstand', $this->startDate, $this->endDate);
    }
}
