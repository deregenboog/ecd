<?php

namespace OekBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use OekBundle\Entity\DeelnameStatus as DS;
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

    /**
     * Aantallen zijn cummulatief:
     *  - aangemeld = aangemeld + gestart + gevolgd + afgerond
     *  - gestart = gestart + gevolgd + afgerond
     *  - gevolgd = gestart + gevolgd
     *  - afgerond = afgerond
     *
     * @see https://github.com/deregenboog/ecd/issues/1037
     */
    protected function init()
    {
        $this->tables[DS::STATUS_AANGEMELD] = $this->repository->countByGroepAndTraining(
            [DS::STATUS_AANGEMELD, DS::STATUS_GESTART, DS::STATUS_GEVOLGD, DS::STATUS_AFGEROND],
            $this->startDate,
            $this->endDate
        );

        $this->tables[DS::STATUS_GESTART] = $this->repository->countByGroepAndTraining(
            [DS::STATUS_GESTART, DS::STATUS_GEVOLGD, DS::STATUS_AFGEROND],
            $this->startDate,
            $this->endDate
        );

        $this->tables[DS::STATUS_GEVOLGD] = $this->repository->countByGroepAndTraining(
            [DS::STATUS_GEVOLGD, DS::STATUS_AFGEROND],
            $this->startDate,
            $this->endDate
        );

        $this->tables[DS::STATUS_AFGEROND] = $this->repository->countByGroepAndTraining(
            [DS::STATUS_AFGEROND],
            $this->startDate,
            $this->endDate
        );
    }

    protected function build()
    {
        foreach ($this->tables as $status => $table) {
            $table = new Table($table, $this->xPath, $this->yPath, $this->nPath);
            $table->setStartDate($this->startDate)->setEndDate($this->endDate);
            $table->setNullAsNill(true);

            $this->reports[] = [
                'title' => ucfirst($status),
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ];
        }
    }
}
