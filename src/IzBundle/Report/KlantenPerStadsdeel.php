<?php

namespace IzBundle\Report;

class KlantenPerStadsdeel extends AbstractKlantenReport
{
    protected $title = 'Deelnemers per stadsdeel';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal deelnemers met koppeling (op basis van start- en einddatum van koppeling).';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $this->beginstand = $this->repository->countByStadsdeel('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countByStadsdeel('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countByStadsdeel('afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countByStadsdeel('eindstand', $this->startDate, $this->endDate);
    }
}
