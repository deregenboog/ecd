<?php

namespace IzBundle\Report;

class HulpvragenPerProjectStadsdeel extends AbstractHulpvragenReport
{
    protected $title = 'Hulpvragen per project en stadsdeel';

    protected $xPath = 'projectnaam';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Project';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $this->beginstand = $this->repository->countHulpvragenByProjectAndStadsdeel('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countHulpvragenByProjectAndStadsdeel('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countHulpvragenByProjectAndStadsdeel('afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countHulpvragenByProjectAndStadsdeel('eindstand', $this->startDate, $this->endDate);
    }
}
