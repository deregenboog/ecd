<?php

namespace IzBundle\Report;

use IzBundle\Repository\IzVrijwilligerRepository;

class VrijwilligersTotaal extends AbstractVrijwilligersReport
{
    protected $title = 'Vrijwilligers totaal';

    protected $nPath = 'aantal';

    protected function init()
    {
        $this->beginstand = $this->repository->count(
            IzVrijwilligerRepository::REPORT_BEGINSTAND,
            $this->startDate,
            $this->endDate
        );
        $this->gestart = $this->repository->count(
            IzVrijwilligerRepository::REPORT_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->nieuwGestart = $this->repository->count(
            IzVrijwilligerRepository::REPORT_NIEUW_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->afgesloten = $this->repository->count(
            IzVrijwilligerRepository::REPORT_AFGESLOTEN,
            $this->startDate,
            $this->endDate
        );
        $this->eindstand = $this->repository->count(
            IzVrijwilligerRepository::REPORT_EINDSTAND,
            $this->startDate,
            $this->endDate
        );
    }
}
