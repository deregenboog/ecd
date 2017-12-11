<?php

namespace DagbestedingBundle\Report;

use DagbestedingBundle\Service\DagdeelDaoInterface;
use AppBundle\Report\AbstractReport;

class DagdelenPerDeelnemer extends AbstractReport
{
    protected $title = 'Dagdelen per deelnemer';

    protected $xPath = 'aanwezigheid';

    protected $yPath = 'naam';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aanwezigheid';

    protected $yDescription = 'Deelnemer';

    protected $tables = [];

    public function __construct(DagdeelDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $date = new \DateTime($this->startDate->format('Y-m-01'));
        $end = new \DateTime($this->endDate->format('Y-m-01'));

        while ($date <= $end) {
            $this->tables[$date->format('m-Y')] = $this->dao->countByDeelnemer(
                new \DateTime($date->format('Y-m-01')),
                (new \DateTime($date->format('Y-m-01')))->modify('+1 month')->modify('-1 day')
            );
            $date->modify('+1 month');
        }
    }
}
