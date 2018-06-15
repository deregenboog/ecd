<?php

namespace DagbestedingBundle\Report;

use AppBundle\Report\AbstractReport;
use DagbestedingBundle\Service\TrajectDaoInterface;

class TrajectenPerAfsluiting extends AbstractReport
{
    protected $title = 'Trajecten per resultaat';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Resultaat';

    protected $tables = [];

    public function __construct(TrajectDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables['Gestopt'] = $this->dao->countByAfsluiting(TrajectDaoInterface::FASE_GESTOPT, $this->startDate, $this->endDate);
    }
}
