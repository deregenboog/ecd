<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;
use ClipBundle\Service\ContactmomentDaoInterface;

class ContactmomentenPerLeeftijdscategorie extends AbstractReport
{
    protected $title = 'Contactmomenten per leeftijdscategorie';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Leeftijdscategorie';

    protected $tables = [];

    public function __construct(ContactmomentDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[''] = $this->dao->countByLeeftijdscategorie($this->startDate, $this->endDate);
    }
}
