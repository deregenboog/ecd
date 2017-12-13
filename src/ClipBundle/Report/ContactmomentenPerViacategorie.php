<?php

namespace ClipBundle\Report;

use ClipBundle\Service\ContactmomentDaoInterface;
use AppBundle\Report\AbstractReport;

class ContactmomentenPerViacategorie extends AbstractReport
{
    protected $title = 'Contactmomenten per hoe bekend';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Hoe bekend';

    protected $tables = [];

    public function __construct(ContactmomentDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByViacategorie($this->startDate, $this->endDate);
    }
}
