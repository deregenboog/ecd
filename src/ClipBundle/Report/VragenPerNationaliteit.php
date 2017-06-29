<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;

class VragenPerNationaliteit extends AbstractReport
{
    protected $title = 'Vragen per nationaliteit';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Nationaliteit';

    protected $tables = [];

    public function __construct(VraagDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[VraagDaoInterface::FASE_BEGINSTAND] = $this->dao->countByNationaliteit(VraagDaoInterface::FASE_BEGINSTAND, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_GESTART] = $this->dao->countByNationaliteit(VraagDaoInterface::FASE_GESTART, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_AFGESLOTEN] = $this->dao->countByNationaliteit(VraagDaoInterface::FASE_AFGESLOTEN, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_EINDSTAND] = $this->dao->countByNationaliteit(VraagDaoInterface::FASE_EINDSTAND, $this->startDate, $this->endDate);
    }
}
