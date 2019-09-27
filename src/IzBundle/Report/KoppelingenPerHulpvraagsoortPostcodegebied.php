<?php

namespace IzBundle\Report;

class KoppelingenPerHulpvraagsoortPostcodegebied extends AbstractKoppelingenReport
{
    protected $title = 'Koppelingen per hulpvraagsoort en postcodegebied';

    protected $xPath = 'hulpvraagsoortnaam';

    protected $yPath = 'ggwgebiednaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Hulpvraagsoort';

    protected $yDescription = 'Postcodegebied';

    protected function init()
    {
        $this->beginstand = $this->repository->countKoppelingenByHulpvraagsoortAndPostcodegebied('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countKoppelingenByHulpvraagsoortAndPostcodegebied('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countKoppelingenByHulpvraagsoortAndPostcodegebied('afgesloten', $this->startDate, $this->endDate);
        $this->succesvolAfgesloten = $this->repository->countKoppelingenByHulpvraagsoortAndPostcodegebied('succesvol_afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countKoppelingenByHulpvraagsoortAndPostcodegebied('eindstand', $this->startDate, $this->endDate);
    }
}
