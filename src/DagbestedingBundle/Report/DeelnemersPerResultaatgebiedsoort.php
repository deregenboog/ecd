<?php

namespace DagbestedingBundle\Report;

use DagbestedingBundle\Service\DeelnemerDaoInterface;
use AppBundle\Report\AbstractReport;

class DeelnemersPerResultaatgebiedsoort extends AbstractReport
{
    protected $title = 'Deelnemers per resultaatgebied';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Resultaatgebied';

    protected $tables = [];

    public function __construct(DeelnemerDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables['Beginstand'] = $this->dao->countByResultaatgebiedsoort(DeelnemerDaoInterface::FASE_BEGINSTAND, $this->startDate, $this->endDate);
        $this->tables['Gestart'] = $this->dao->countByResultaatgebiedsoort(DeelnemerDaoInterface::FASE_GESTART, $this->startDate, $this->endDate);
        $this->tables['Gestopt'] = $this->dao->countByResultaatgebiedsoort(DeelnemerDaoInterface::FASE_GESTOPT, $this->startDate, $this->endDate);
        $this->tables['Eindstand'] = $this->dao->countByResultaatgebiedsoort(DeelnemerDaoInterface::FASE_EINDSTAND, $this->startDate, $this->endDate);
    }
}
