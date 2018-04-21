<?php

namespace IzBundle\Report;

class KoppelingenPerProjectStadsdeel extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per project en stadsdeel';

    protected $xPath = 'projectnaam';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Project';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingenByProjectAndStadsdeel('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingenByProjectAndStadsdeel('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countKoppelingenByProjectAndStadsdeel('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countKoppelingenByProjectAndStadsdeel('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countKoppelingenByProjectAndStadsdeel('eindstand', $this->startDate, $this->endDate);
    }
}
