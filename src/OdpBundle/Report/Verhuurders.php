<?php

namespace OdpBundle\Report;

use AppBundle\Report\AbstractReport;
use OdpBundle\Service\VerhuurderDaoInterface;

class Verhuurders extends AbstractReport
{
    protected $title = 'Verhuurders';

    protected $nPath = 'aantal';

    protected $xDescription = 'Aantal verhuurders %s binnen de opgegeven periode';

    public function __construct(VerhuurderDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables['Aangemeld'] = $this->dao->countAangemeld($this->startDate, $this->endDate);
        $this->tables['Gekopppeld'] = $this->dao->countGekoppeld($this->startDate, $this->endDate);
        $this->tables['Ontkoppeld'] = $this->dao->countOntkoppeld($this->startDate, $this->endDate);
        $this->tables['Afgesloten'] = $this->dao->countAfgesloten($this->startDate, $this->endDate);
    }

    protected function build()
    {
        parent::build();

        $replacements = ['aangemeld', 'gekoppeld', 'ontkoppeld', 'afgesloten'];

        foreach ($this->reports as $i => $report) {
            $this->reports[$i]['xDescription'] = sprintf($report['xDescription'], $replacements[$i]);
        }
    }
}
