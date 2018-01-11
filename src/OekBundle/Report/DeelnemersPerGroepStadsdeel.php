<?php

namespace OekBundle\Report;

use AppBundle\Report\Table;
use OekBundle\Entity\DeelnameStatus;
use OekBundle\Repository\KlantRepository;
use AppBundle\Report\AbstractReport;

class DeelnemersPerGroepStadsdeel extends AbstractReport
{
    protected $title = 'Deelnemers per groep en stadsdeel';

    protected $xPath = 'stadsdeel';

    protected $yPath = 'groepnaam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Stadsdeel';

    protected $yDescription = 'Groep';

    protected $tables = [];

    public function __construct(KlantRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function init()
    {
        $statussen = DeelnameStatus::getAllStatuses();

        foreach ($statussen as $status) {
            $this->tables[$status] = $this->repository->countByGroepAndStadsdeel($status, $this->startDate, $this->endDate);
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
