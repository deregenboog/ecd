<?php

namespace OdpBundle\Report;

use AppBundle\Report\AbstractReport;
use OdpBundle\Service\HuurovereenkomstDaoInterface;

class KoppelingenPerWoningbouwcorporatie extends AbstractReport
{
    protected $title = 'Koppelingen';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = 'Actieve koppelingen binnen de opgegeven periode';

    public function __construct(HuurovereenkomstDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables['Koppelingen per vorm'] = $this->dao->countByVorm($this->startDate, $this->endDate);
        $this->tables['Koppelingen per woningbouwcorporatie'] = $this->dao->countByWoningbouwcorporatie($this->startDate, $this->endDate);
    }

    protected function build()
    {
        parent::build();

        $this->reports[0]['yDescription'] = 'Vorm';
        $this->reports[1]['yDescription'] = 'Woningbouwcorporatie';
    }
}
