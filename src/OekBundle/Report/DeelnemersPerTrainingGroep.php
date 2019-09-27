<?php

namespace OekBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use OekBundle\Entity\DeelnameStatus;
use OekBundle\Repository\DeelnemerRepository;

class DeelnemersPerTrainingGroep extends AbstractReport
{
    protected $title = 'Deelnemers per naam training en groep';

    protected $xPath = 'groepnaam';

    protected $yPath = 'trainingnaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Groep';

    protected $yDescription = 'Naam training';

    protected $tables = [];

    public function __construct(DeelnemerRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function init()
    {
        $statussen = DeelnameStatus::getAllStatuses();

        foreach ($statussen as $status) {
            $this->tables[$status] = $this->repository->countByGroepAndTraining($status, $this->startDate, $this->endDate);
        }
    }

    protected function build()
    {
        foreach ($this->tables as $status => $table) {
            $table = new Table($table, $this->xPath, $this->yPath, $this->nPath);
            $table->setStartDate($this->startDate)->setEndDate($this->endDate);

            $this->reports[] = [
                'title' => ucfirst($status),
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ];
        }
    }
}
