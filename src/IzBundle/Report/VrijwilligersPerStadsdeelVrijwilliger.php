<?php

namespace IzBundle\Report;

use IzBundle\Repository\IzVrijwilligerRepository;

class VrijwilligersPerStadsdeelVrijwilliger extends AbstractVrijwilligersReport
{
    protected $title = 'Vrijwilligers per stadsdeel van de vrijwilliger';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $yDescription = 'Stadsdeel van de vrijwilliger';

    protected function init()
    {
        $this->beginstand = $this->repository->countByStadsdeelVrijwilliger(
            IzVrijwilligerRepository::REPORT_BEGINSTAND,
            $this->startDate,
            $this->endDate
        );
        $this->gestart = $this->repository->countByStadsdeelVrijwilliger(
            IzVrijwilligerRepository::REPORT_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->nieuwGestart = $this->repository->countByStadsdeelVrijwilliger(
            IzVrijwilligerRepository::REPORT_NIEUW_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->afgesloten = $this->repository->countByStadsdeelVrijwilliger(
            IzVrijwilligerRepository::REPORT_AFGESLOTEN,
            $this->startDate,
            $this->endDate
        );
        $this->eindstand = $this->repository->countByStadsdeelVrijwilliger(
            IzVrijwilligerRepository::REPORT_EINDSTAND,
            $this->startDate,
            $this->endDate
        );
    }
}
