<?php

namespace ClipBundle\Report;

use ClipBundle\Service\ContactmomentDaoInterface;
use AppBundle\Report\AbstractReport;

class ContactmomentenPerNationaliteit extends AbstractReport
{
    protected $title = 'Contactmomenten per nationaliteit';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Nationaliteit';

    protected $tables = [];

    public function __construct(ContactmomentDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByNationaliteit($this->startDate, $this->endDate);
    }
}
