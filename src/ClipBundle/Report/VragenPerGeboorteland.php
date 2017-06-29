<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;

class VragenPerGeboorteland extends AbstractReport
{
    protected $title = 'Vragen per geboorteland';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Geboorteland';

    protected $tables = [];

    public function __construct(VraagDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[VraagDaoInterface::FASE_BEGINSTAND] = $this->dao->countByGeboorteland(VraagDaoInterface::FASE_BEGINSTAND, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_GESTART] = $this->dao->countByGeboorteland(VraagDaoInterface::FASE_GESTART, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_AFGESLOTEN] = $this->dao->countByGeboorteland(VraagDaoInterface::FASE_AFGESLOTEN, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_EINDSTAND] = $this->dao->countByGeboorteland(VraagDaoInterface::FASE_EINDSTAND, $this->startDate, $this->endDate);
    }
}
