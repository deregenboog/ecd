<?php

namespace IzBundle\Report;

use IzBundle\Repository\IzKlantRepository;

class KlantenTotaalNamenlijst extends AbstractKlantenNamenlijstReport
{
    protected $title = 'Deelnemers totaal (namenlijst)';

    protected function init()
    {
        $this->beginstand = $this->repository->select(
            IzKlantRepository::REPORT_BEGINSTAND,
            $this->startDate,
            $this->endDate
        );
        $this->gestart = $this->repository->select(
            IzKlantRepository::REPORT_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->nieuwGestart = $this->repository->select(
            IzKlantRepository::REPORT_NIEUW_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->afgesloten = $this->repository->select(
            IzKlantRepository::REPORT_AFGESLOTEN,
            $this->startDate,
            $this->endDate
        );
        $this->eindstand = $this->repository->select(
            IzKlantRepository::REPORT_EINDSTAND,
            $this->startDate,
            $this->endDate
        );
    }
}
