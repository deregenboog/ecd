<?php

namespace OdpBundle\Report;

use OdpBundle\Service\HuurovereenkomstDao;
use AppBundle\Report\AbstractReport;

class HuurovereenkomstenPerWoningbouwcorporatie extends AbstractReport
{
    protected $title = 'Huurovereenkomsten per woningbouwcorporatie';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = 'Koppelingen waarvan de looptijd overlapt met de opgegeven periode';

    protected $yDescription = 'Woningbouwcorporatie';

    protected $tables = [];

    public function __construct(HuurovereenkomstDao $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByWoningbouwcorporatie($this->startDate, $this->endDate);
    }
}
