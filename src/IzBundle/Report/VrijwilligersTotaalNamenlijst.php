<?php

namespace IzBundle\Report;

use IzBundle\Repository\IzVrijwilligerRepository;

class VrijwilligersTotaalNamenlijst extends AbstractVrijwilligersNamenlijstReport
{
    protected $title = 'Vrijwilligers totaal (namenlijst)';

    protected function init()
    {
        $this->beginstand = $this->repository->select(
            IzVrijwilligerRepository::REPORT_BEGINSTAND,
            $this->startDate,
            $this->endDate
        );
        $this->gestart = $this->repository->select(
            IzVrijwilligerRepository::REPORT_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->nieuwGestart = $this->repository->select(
            IzVrijwilligerRepository::REPORT_NIEUW_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->afgesloten = $this->repository->select(
            IzVrijwilligerRepository::REPORT_AFGESLOTEN,
            $this->startDate,
            $this->endDate
        );
        $this->eindstand = $this->repository->select(
            IzVrijwilligerRepository::REPORT_EINDSTAND,
            $this->startDate,
            $this->endDate
        );
    }
}
