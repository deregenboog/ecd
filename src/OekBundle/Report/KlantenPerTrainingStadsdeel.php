<?php

namespace OekBundle\Report;

use AppBundle\Report\Table;
use OekBundle\Entity\OekDeelnameStatus;
use OekBundle\Repository\OekKlantRepository;

class KlantenPerTrainingStadsdeel extends AbstractReport
{
    protected $title = 'Deelnemers per training en stadsdeel';

    protected $xPath = 'stadsdeel';

    protected $yPath = 'training';

    protected $nPath = 'aantal';

    protected $xDescription = 'Stadsdeel';

    protected $yDescription = 'Training';

    protected $tables = [];

    public function __construct(OekKlantRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function init()
    {
        $statussen = OekDeelnameStatus::getAllStatuses();

        foreach ($statussen as $status) {
            $this->tables[$status] = $this->repository->countByTrainingAndStadsdeel($status, $this->startDate, $this->endDate);
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
