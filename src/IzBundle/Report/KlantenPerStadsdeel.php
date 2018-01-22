<?php

namespace IzBundle\Report;

use IzBundle\Repository\IzKlantRepository;

class KlantenPerStadsdeel extends AbstractKlantenReport
{
    protected $title = 'Deelnemers per stadsdeel';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $yDescription = 'Stadsdeel';

    protected function init()
    {
        $this->beginstand = $this->repository->countByStadsdeel(
            IzKlantRepository::REPORT_BEGINSTAND,
            $this->startDate,
            $this->endDate
        );
        $this->gestart = $this->repository->countByStadsdeel(
            IzKlantRepository::REPORT_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->nieuwGestart = $this->repository->countByStadsdeel(
            IzKlantRepository::REPORT_NIEUW_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->afgesloten = $this->repository->countByStadsdeel(
            IzKlantRepository::REPORT_AFGESLOTEN,
            $this->startDate,
            $this->endDate
        );
        $this->eindstand = $this->repository->countByStadsdeel(
            IzKlantRepository::REPORT_EINDSTAND,
            $this->startDate,
            $this->endDate
        );
    }
}
