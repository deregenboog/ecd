<?php

namespace OekBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use OekBundle\Repository\DeelnemerRepository;

abstract class AbstractDeelnemersVerwezen extends AbstractReport
{
    public const DOOR = 'door';

    public const NAAR = 'naar';

    protected $verwezen;

    protected $title = 'Deelnemers verwezen ';

    protected $yPath = 'verwijzingsoort';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal deelnemers';

    protected $yDescription = 'Verwijzing';

    protected $table;

    public function __construct(DeelnemerRepository $repository)
    {
        $this->repository = $repository;

        $this->title = $this->title.$this->verwezen;
    }

    protected function init()
    {
        $this->table = $this->repository->countByVerwijzing($this->verwezen, $this->startDate, $this->endDate);
    }

    protected function build()
    {
        $table = new Table($this->table, $this->xPath, $this->yPath, $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports = [
            [
                'title' => 'Verwijzingen '.$this->verwezen,
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ],
        ];
    }
}
