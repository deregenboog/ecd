<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;
use ClipBundle\Service\ContactmomentDaoInterface;
use AppBundle\Report\AbstractReport;

class ContactmomentenPerCommunicatiekanaal extends AbstractReport
{
    protected $title = 'Contactmomenten per locatie';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Locatie';

    protected $tables = [];

    public function __construct(ContactmomentDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByCommunicatiekanaal($this->startDate, $this->endDate);
    }
}
