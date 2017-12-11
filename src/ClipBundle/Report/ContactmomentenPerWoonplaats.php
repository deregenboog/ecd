<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;
use ClipBundle\Service\ContactmomentDaoInterface;
use AppBundle\Report\AbstractReport;

class ContactmomentenPerWoonplaats extends AbstractReport
{
    protected $title = 'Contactmomenten per woonplaats';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Woonplaats';

    protected $tables = [];

    public function __construct(ContactmomentDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByWoonplaats($this->startDate, $this->endDate);
    }
}
