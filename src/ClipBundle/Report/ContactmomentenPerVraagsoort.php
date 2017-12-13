<?php

namespace ClipBundle\Report;

use ClipBundle\Service\ContactmomentDaoInterface;
use AppBundle\Report\AbstractReport;

class ContactmomentenPerVraagsoort extends AbstractReport
{
    protected $title = 'Contactmomenten per vraagsoort';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Vraagsoort';

    protected $tables = [];

    public function __construct(ContactmomentDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByVraagsoort($this->startDate, $this->endDate);
    }
}
