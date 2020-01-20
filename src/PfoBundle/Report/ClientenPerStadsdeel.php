<?php

namespace PfoBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use PfoBundle\Repository\ClientRepository;

class ClientenPerStadsdeel extends AbstractReport
{
    protected $title = 'Personen per stadsdeel';

    protected $yPath = 'stadsdeelnaam';

    protected $nPath = 'aantal';

    protected $yDescription = 'Stadsdeel';

    protected $table;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function init()
    {
        $this->table = $this->repository->countByStadsdeel($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $table = new Table($this->table, $this->xPath, $this->yPath, $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports = [
            [
                'title' => 'Personen',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ],
        ];
    }
}
