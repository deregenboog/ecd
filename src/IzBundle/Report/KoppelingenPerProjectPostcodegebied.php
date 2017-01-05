<?php

namespace IzBundle\Report;

class KoppelingenPerProjectPostcodegebied extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per project en postcodgebied';

    protected $xPath = 'project';

    protected $yPath = 'postcodegebied';

    protected $nPath = 'aantal';

    protected $xDescription = 'Project';

    protected $yDescription = 'Postcodegebied';

    protected function init()
    {
        $this->beginstand = $this->repository->countByProjectAndPostcodegebied('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countByProjectAndPostcodegebied('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countByProjectAndPostcodegebied('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countByProjectAndPostcodegebied('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countByProjectAndPostcodegebied('eindstand', $this->startDate, $this->endDate);
    }
}
