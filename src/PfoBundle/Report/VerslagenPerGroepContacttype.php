<?php

namespace PfoBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use PfoBundle\Repository\VerslagRepository;

class VerslagenPerGroepContacttype extends AbstractReport
{
    protected $title = 'Contactmomenten per groep en contacttype';

    protected $xPath = 'contacttype';

    protected $yPath = 'groepnaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Contacttype';

    protected $yDescription = 'Groep';

    protected $table;

    public function __construct(VerslagRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function init()
    {
        $this->table = $this->repository->countByGroepAndContacttype($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $table = new Table($this->table, $this->xPath, $this->yPath, $this->nPath);
        $table->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports = [
            [
                'title' => 'Contactmomenten',
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ],
        ];
    }
}
