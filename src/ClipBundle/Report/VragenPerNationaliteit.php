<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;
use AppBundle\Report\AbstractReport;

class VragenPerNationaliteit extends AbstractReport
{
    protected $title = 'Vragen per nationaliteit';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Nationaliteit';

    protected $tables = [];

    public function __construct(VraagDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByNationaliteit($this->startDate, $this->endDate);
    }
}
