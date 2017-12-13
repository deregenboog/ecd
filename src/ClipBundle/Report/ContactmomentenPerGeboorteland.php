<?php

namespace ClipBundle\Report;

use ClipBundle\Service\ContactmomentDaoInterface;
use AppBundle\Report\AbstractReport;

class ContactmomentenPerGeboorteland extends AbstractReport
{
    protected $title = 'Contactmomenten per geboorteland';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Geboorteland';

    protected $tables = [];

    public function __construct(ContactmomentDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByGeboorteland($this->startDate, $this->endDate);
    }
}
