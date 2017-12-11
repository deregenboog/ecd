<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;
use AppBundle\Report\AbstractReport;

class VragenPerLeeftijdscategorie extends AbstractReport
{
    protected $title = 'Vragen per leeftijdscategorie';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Leeftijdscategorie';

    protected $tables = [];

    public function __construct(VraagDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByLeeftijdscategorie($this->startDate, $this->endDate);
    }
}
