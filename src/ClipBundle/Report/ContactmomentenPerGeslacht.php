<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;
use ClipBundle\Service\ContactmomentDaoInterface;
use AppBundle\Report\AbstractReport;

class ContactmomentenPerGeslacht extends AbstractReport
{
    protected $title = 'Contactmomenten per geslacht';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Geslacht';

    protected $tables = [];

    public function __construct(ContactmomentDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByGeslacht($this->startDate, $this->endDate);
    }
}
