<?php

namespace IzBundle\Report;

class KoppelingenPerHulpvraagsoortStadsdeel extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per hulpvraagsoort en stadsdeel';

    protected $xPath = 'hulpvraagsoortnaam';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Hulpvraagsoort';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingenByHulpvraagsoortAndStadsdeel('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingenByHulpvraagsoortAndStadsdeel('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countKoppelingenByHulpvraagsoortAndStadsdeel('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countKoppelingenByHulpvraagsoortAndStadsdeel('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countKoppelingenByHulpvraagsoortAndStadsdeel('eindstand', $this->startDate, $this->endDate);
    }
}
