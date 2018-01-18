<?php

namespace IzBundle\Report;

use IzBundle\Repository\IzVrijwilligerRepository;

class VrijwilligersPerStadsdeelKlant extends AbstractVrijwilligersReport
{
    protected $title = 'Vrijwilligers per stadsdeel van de deelnemer';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $yDescription = 'Stadsdeel van de deelnemer';

    protected function init()
    {
        $this->beginstand = $this->repository->countByStadsdeelKlant(
            IzVrijwilligerRepository::REPORT_BEGINSTAND,
            $this->startDate,
            $this->endDate
        );
        $this->gestart = $this->repository->countByStadsdeelKlant(
            IzVrijwilligerRepository::REPORT_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->nieuwGestart = $this->repository->countByStadsdeelKlant(
            IzVrijwilligerRepository::REPORT_NIEUW_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->afgesloten = $this->repository->countByStadsdeelKlant(
            IzVrijwilligerRepository::REPORT_AFGESLOTEN,
            $this->startDate,
            $this->endDate
        );
        $this->eindstand = $this->repository->countByStadsdeelKlant(
            IzVrijwilligerRepository::REPORT_EINDSTAND,
            $this->startDate,
            $this->endDate
        );
    }
}
