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
        $this->beginstand = $this->repository->countByStadsdeel('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countByStadsdeel('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countByStadsdeel('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countByStadsdeel('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countByStadsdeel('eindstand', $this->startDate, $this->endDate);
    }
}
