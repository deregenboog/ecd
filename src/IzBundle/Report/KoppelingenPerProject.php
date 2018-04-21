<?php

namespace IzBundle\Report;

class KoppelingenPerProject extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per project';

    protected $yPath = 'projectnaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal koppelingen';

    protected $yDescription = 'Project';

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingenByProject('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingenByProject('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countKoppelingenByProject('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countKoppelingenByProject('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countKoppelingenByProject('eindstand', $this->startDate, $this->endDate);
    }
}
