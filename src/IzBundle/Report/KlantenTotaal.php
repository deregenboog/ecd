<?php

namespace IzBundle\Report;

use IzBundle\Repository\IzKlantRepository;

class KlantenTotaal extends AbstractKlantenReport
{
    protected $title = 'Deelnemers totaal';

    protected $nPath = 'aantal';

    protected function init()
    {
        $this->beginstand = $this->repository->count(
            IzKlantRepository::REPORT_BEGINSTAND,
            $this->startDate,
            $this->endDate
        );
        $this->gestart = $this->repository->count(
            IzKlantRepository::REPORT_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->nieuwGestart = $this->repository->count(
            IzKlantRepository::REPORT_NIEUW_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->afgesloten = $this->repository->count(
            IzKlantRepository::REPORT_AFGESLOTEN,
            $this->startDate,
            $this->endDate
        );
        $this->eindstand = $this->repository->count(
            IzKlantRepository::REPORT_EINDSTAND,
            $this->startDate,
            $this->endDate
        );
    }
}
