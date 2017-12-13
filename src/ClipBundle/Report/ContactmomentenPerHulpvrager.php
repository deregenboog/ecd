<?php

namespace ClipBundle\Report;

use ClipBundle\Service\ContactmomentDaoInterface;
use AppBundle\Report\AbstractReport;

class ContactmomentenPerHulpvrager extends AbstractReport
{
    protected $title = 'Contactmomenten per hulpvrager';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Hulpvrager';

    protected $tables = [];

    public function __construct(ContactmomentDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByHulpvrager($this->startDate, $this->endDate);
    }
}
