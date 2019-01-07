<?php

namespace IzBundle\Report;

class KoppelingenPerPostcodegebied extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per postcodegebied';

    protected $yPath = 'ggwgebiednaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal koppelingen';

    protected $yDescription = 'Postcodegebied';

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingenByPostcodegebied('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingenByPostcodegebied('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countKoppelingenByPostcodegebied('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countKoppelingenByPostcodegebied('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countKoppelingenByPostcodegebied('eindstand', $this->startDate, $this->endDate);
    }
}
