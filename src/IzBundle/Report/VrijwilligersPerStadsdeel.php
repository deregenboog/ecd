<?php

namespace IzBundle\Report;

class VrijwilligersPerStadsdeel extends AbstractVrijwilligersReport
{
    protected $title = 'Vrijwilligers per stadsdeel';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal vrijwilligers met intake en hulpaanbod (op basis van intakedatum en afsluitdatum)';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $this->beginstand = $this->repository->countByStadsdeel('beginstand', $this->startDate, $this->endDate);
        $this->gestart = $this->repository->countByStadsdeel('gestart', $this->startDate, $this->endDate);
        $this->afgesloten = $this->repository->countByStadsdeel('afgesloten', $this->startDate, $this->endDate);
        $this->eindstand = $this->repository->countByStadsdeel('eindstand', $this->startDate, $this->endDate);
    }
}
