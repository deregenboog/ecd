<?php

namespace OekBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use OekBundle\Repository\DeelnameRepository;

class DeelnamesPerStadsdeel extends AbstractReport
{
    protected $title = 'Deelnames per stadsdeel';

    //    protected $xPath = 'stadsdeel';

    protected $yPath = 'stadsdeel';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal deelnemers';

    protected $yDescription = 'Stadsdeel';

    protected $table;

    /**
     * @var DeelnameRepository
     */
    protected $repository;

    public function __construct(DeelnameRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function init()
    {
        $this->table = $this->repository->getAantalDeelnamesPerStadsdeel($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $table = new Table($this->table, $this->xPath, $this->yPath, $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports = [
            [
                'title' => 'Deelnames per stadsdeel',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ],
        ];
    }
}
