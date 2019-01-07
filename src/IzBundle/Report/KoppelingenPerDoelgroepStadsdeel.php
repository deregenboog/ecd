<?php

namespace IzBundle\Report;

class KoppelingenPerDoelgroepStadsdeel extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per doelgroep en stadsdeel';

    protected $xPath = 'doelgroepnaam';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Doelgroep';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingenByDoelgroepAndStadsdeel('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingenByDoelgroepAndStadsdeel('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countKoppelingenByDoelgroepAndStadsdeel('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countKoppelingenByDoelgroepAndStadsdeel('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countKoppelingenByDoelgroepAndStadsdeel('eindstand', $this->startDate, $this->endDate);
    }
}
