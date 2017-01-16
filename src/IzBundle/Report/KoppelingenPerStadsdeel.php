<?php

namespace IzBundle\Report;

class KoppelingenPerStadsdeel extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per stadsdeel';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal koppelingen';

    protected $yDescription = 'Project';

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingenByStadsdeel('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingenByStadsdeel('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countKoppelingenByStadsdeel('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countKoppelingenByStadsdeel('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countKoppelingenByStadsdeel('eindstand', $this->startDate, $this->endDate);
    }
}
