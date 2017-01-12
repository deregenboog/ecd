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
        $this->beginstand = $this->repository->countByCoordinator('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countByCoordinator('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countByCoordinator('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countByCoordinator('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countByCoordinator('eindstand', $this->startDate, $this->endDate);
    }
}
