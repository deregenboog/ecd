<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;
use AppBundle\Report\AbstractReport;

class VragenPerViacategorie extends AbstractReport
{
    protected $title = 'Vragen per hoe bekend';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Hoe bekend';

    protected $tables = [];

    public function __construct(VraagDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByViacategorie($this->startDate, $this->endDate);
    }
}
