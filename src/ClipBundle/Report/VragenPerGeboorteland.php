<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;
use AppBundle\Report\AbstractReport;

class VragenPerGeboorteland extends AbstractReport
{
    protected $title = 'Vragen per geboorteland';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Geboorteland';

    protected $tables = [];

    public function __construct(VraagDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByGeboorteland($this->startDate, $this->endDate);
    }
}
