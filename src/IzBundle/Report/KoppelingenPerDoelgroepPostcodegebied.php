<?php

namespace IzBundle\Report;

class KoppelingenPerDoelgroepPostcodegebied extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per doelgroep en postcodegebied';

    protected $xPath = 'doelgroepnaam';

    protected $yPath = 'ggwgebiednaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Doelgroep';

    protected $yDescription = 'Postcodegebied';

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingenByDoelgroepAndPostcodegebied('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingenByDoelgroepAndPostcodegebied('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countKoppelingenByDoelgroepAndPostcodegebied('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countKoppelingenByDoelgroepAndPostcodegebied('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countKoppelingenByDoelgroepAndPostcodegebied('eindstand', $this->startDate, $this->endDate);
    }
}
