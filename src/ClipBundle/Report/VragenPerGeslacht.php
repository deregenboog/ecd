<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;
use AppBundle\Report\AbstractReport;

class VragenPerGeslacht extends AbstractReport
{
    protected $title = 'Vragen per geslacht';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Geslacht';

    protected $tables = [];

    public function __construct(VraagDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByGeslacht($this->startDate, $this->endDate);
    }
}
