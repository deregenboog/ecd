<?php

namespace IzBundle\Report;

use IzBundle\Repository\IzVrijwilligerRepository;

class VrijwilligersPerStadsdeel extends AbstractVrijwilligersReport
{
    protected $title = 'Vrijwilligers per stadsdeel';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $this->beginstand = $this->repository->countByStadsdeel(
            IzVrijwilligerRepository::REPORT_BEGINSTAND,
            $this->startDate,
            $this->endDate
        );
        $this->gestart = $this->repository->countByStadsdeel(
            IzVrijwilligerRepository::REPORT_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->nieuwGestart = $this->repository->countByStadsdeel(
            IzVrijwilligerRepository::REPORT_NIEUW_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->afgesloten = $this->repository->countByStadsdeel(
            IzVrijwilligerRepository::REPORT_AFGESLOTEN,
            $this->startDate,
            $this->endDate
        );
        $this->eindstand = $this->repository->countByStadsdeel(
            IzVrijwilligerRepository::REPORT_EINDSTAND,
            $this->startDate,
            $this->endDate
        );
    }
}
