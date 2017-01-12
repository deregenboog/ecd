<?php

namespace IzBundle\Report;

class KoppelingenPerPostcodegebied extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per postcodegebied';

    protected $yPath = 'postcodegebied';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal koppelingen';

    protected $yDescription = 'Postcodegebied';

    protected function init()
    {
        $this->beginstand = $this->repository->countByPostcodegebied('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countByPostcodegebied('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countByPostcodegebied('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countByPostcodegebied('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countByPostcodegebied('eindstand', $this->startDate, $this->endDate);
    }
}
