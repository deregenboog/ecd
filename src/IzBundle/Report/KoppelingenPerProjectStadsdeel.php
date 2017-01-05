<?php

namespace IzBundle\Report;

class KoppelingenPerProjectStadsdeel extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per project en stadsdeel';

    protected $xPath = 'project';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Project';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $this->beginstand = $this->repository->countByProjectAndStadsdeel('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countByProjectAndStadsdeel('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countByProjectAndStadsdeel('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countByProjectAndStadsdeel('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countByProjectAndStadsdeel('eindstand', $this->startDate, $this->endDate);
    }
}
