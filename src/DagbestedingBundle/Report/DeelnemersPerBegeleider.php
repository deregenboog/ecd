<?php

namespace DagbestedingBundle\Report;

use AppBundle\Report\AbstractReport;
use DagbestedingBundle\Service\DeelnemerDaoInterface;

class DeelnemersPerBegeleider extends AbstractReport
{
    protected $title = 'Deelnemers per trajectcoach';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Begeleider';

    protected $tables = [];

    public function __construct(DeelnemerDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables['Beginstand'] = $this->dao->countByBegeleider(DeelnemerDaoInterface::FASE_BEGINSTAND, $this->startDate, $this->endDate);
        $this->tables['Gestart'] = $this->dao->countByBegeleider(DeelnemerDaoInterface::FASE_GESTART, $this->startDate, $this->endDate);
        $this->tables['Gestopt'] = $this->dao->countByBegeleider(DeelnemerDaoInterface::FASE_GESTOPT, $this->startDate, $this->endDate);
        $this->tables['Eindstand'] = $this->dao->countByBegeleider(DeelnemerDaoInterface::FASE_EINDSTAND, $this->startDate, $this->endDate);
    }
}
