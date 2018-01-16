<?php

namespace IzBundle\Report;

use IzBundle\Repository\IzVrijwilligerRepository;

class VrijwilligersPerProjectNamenlijst extends AbstractVrijwilligersNamenlijstReport
{
    protected $columns = [
        'Nummer' => 'id',
        'Naam' => 'naam',
        'Project' => 'project',
    ];

    protected $title = 'Vrijwilligers per project (namenlijst)';

    protected function init()
    {
        $this->beginstand = $this->repository->selectByProject(
            IzVrijwilligerRepository::REPORT_BEGINSTAND,
            $this->startDate,
            $this->endDate
        );
        $this->gestart = $this->repository->selectByProject(
            IzVrijwilligerRepository::REPORT_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->nieuwGestart = $this->repository->selectByProject(
            IzVrijwilligerRepository::REPORT_NIEUW_GESTART,
            $this->startDate,
            $this->endDate
        );
        $this->afgesloten = $this->repository->selectByProject(
            IzVrijwilligerRepository::REPORT_AFGESLOTEN,
            $this->startDate,
            $this->endDate
        );
        $this->eindstand = $this->repository->selectByProject(
            IzVrijwilligerRepository::REPORT_EINDSTAND,
            $this->startDate,
            $this->endDate
        );
    }
}
