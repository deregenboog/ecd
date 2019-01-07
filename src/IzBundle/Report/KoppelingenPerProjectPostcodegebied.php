<?php

namespace IzBundle\Report;

class KoppelingenPerProjectPostcodegebied extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per project en postcodgebied';

    protected $xPath = 'projectnaam';

    protected $yPath = 'ggwgebiednaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Project';

    protected $yDescription = 'Postcodegebied';

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingenByProjectAndPostcodegebied('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingenByProjectAndPostcodegebied('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countKoppelingenByProjectAndPostcodegebied('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countKoppelingenByProjectAndPostcodegebied('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countKoppelingenByProjectAndPostcodegebied('eindstand', $this->startDate, $this->endDate);
    }
}
