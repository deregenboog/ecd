<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;
use ClipBundle\Service\ContactmomentDaoInterface;

class ContactmomentenPerViacategorie extends AbstractReport
{
    protected $title = 'Contactmomenten per via-categorie';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Via-categorie';

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
