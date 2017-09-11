<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;

class VragenPerCommunicatiekanaal extends AbstractReport
{
    protected $title = 'Vragen per locatie';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Locatie';

    protected $tables = [];

    public function __construct(VraagDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[VraagDaoInterface::FASE_BEGINSTAND] = $this->dao->countByCommunicatiekanaal(VraagDaoInterface::FASE_BEGINSTAND, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_GESTART] = $this->dao->countByCommunicatiekanaal(VraagDaoInterface::FASE_GESTART, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_AFGESLOTEN] = $this->dao->countByCommunicatiekanaal(VraagDaoInterface::FASE_AFGESLOTEN, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_EINDSTAND] = $this->dao->countByCommunicatiekanaal(VraagDaoInterface::FASE_EINDSTAND, $this->startDate, $this->endDate);
    }
}
