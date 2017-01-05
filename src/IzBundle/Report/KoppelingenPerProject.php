<?php

namespace IzBundle\Report;

class KoppelingenPerProject extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per project';

    protected $yPath = 'project';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal koppelingen';

    protected $yDescription = 'Project';

    protected function init()
    {
        $this->beginstand = $this->repository->countByProject('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countByProject('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countByProject('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countByProject('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countByProject('eindstand', $this->startDate, $this->endDate);
    }
}
