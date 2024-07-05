<?php

namespace TwBundle\Report;

use AppBundle\Report\AbstractReport;
use TwBundle\Service\HuurovereenkomstAfsluitingDaoInterface;

class Afsluitingen extends AbstractReport
{
    protected $title = 'Afsluitingen';

    protected $xPath = 'projectnaam';

    protected $yPath = 'afsluitreden';

    protected $nPath = 'aantal';

    protected $xDescription = 'Project';

    protected $yDescription = 'Afsluitreden huurovereenkomst';

    public function __construct(HuurovereenkomstAfsluitingDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables['Afsluitredenen huurovereenkomst per project'] = $this->dao->countByProject($this->startDate, $this->endDate);
    }
}
