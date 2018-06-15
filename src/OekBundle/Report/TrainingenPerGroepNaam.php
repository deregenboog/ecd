<?php

namespace OekBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use OekBundle\Repository\TrainingRepository;

class TrainingenPerGroepNaam extends AbstractReport
{
    protected $title = 'Trainingen per naam training en groep';

    protected $xPath = 'groepnaam';

    protected $yPath = 'trainingnaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Groep';

    protected $yDescription = 'Naam training';

    protected $table;

    public function __construct(TrainingRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function init()
    {
        $this->table = $this->repository->countByNaamAndGroep($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $table = new Table($this->table, $this->xPath, $this->yPath, $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports = [
            [
                'title' => 'Trainingen',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ],
        ];
    }
}
