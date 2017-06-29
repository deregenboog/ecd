<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;

class VragenPerVraagsoort extends AbstractReport
{
    protected $title = 'Vragen per vraagsoort';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Vraagsoort';

    protected $tables = [];

    public function __construct(VraagDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[VraagDaoInterface::FASE_BEGINSTAND] = $this->dao->countByVraagsoort(VraagDaoInterface::FASE_BEGINSTAND, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_GESTART] = $this->dao->countByVraagsoort(VraagDaoInterface::FASE_GESTART, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_AFGESLOTEN] = $this->dao->countByVraagsoort(VraagDaoInterface::FASE_AFGESLOTEN, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_EINDSTAND] = $this->dao->countByVraagsoort(VraagDaoInterface::FASE_EINDSTAND, $this->startDate, $this->endDate);
    }
}
