<?php

namespace IzBundle\Report;

class KoppelingenTotaal extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen totaal';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal koppelingen';

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingen('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingen('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countKoppelingen('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countKoppelingen('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countKoppelingen('eindstand', $this->startDate, $this->endDate);
    }
}
