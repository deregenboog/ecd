<?php

namespace IzBundle\Report;

class KoppelingenPerCoordinator extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per coördinator';

    protected $yPath = 'coordinator';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal koppelingen';

    protected $yDescription = 'Coördinator';

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingenByCoordinator('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingenByCoordinator('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countKoppelingenByCoordinator('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countKoppelingenByCoordinator('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countKoppelingenByCoordinator('eindstand', $this->startDate, $this->endDate);
    }
}
