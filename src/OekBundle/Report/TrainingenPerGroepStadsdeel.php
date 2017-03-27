<?php

namespace OekBundle\Report;

use AppBundle\Report\Table;
use OekBundle\Repository\OekTrainingRepository;

class TrainingenPerGroepStadsdeel extends AbstractReport
{
    protected $title = 'Trainingen per groep en stadsdeel';

    protected $xPath = 'stadsdeel';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = 'Stadsdeel';

    protected $yDescription = 'Groep';

    protected $table;

    public function __construct(OekTrainingRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function init()
    {
        $this->table = $this->repository->countByGroepAndStadsdeel($this->startDate, $this->endDate);
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