<?php

namespace ClipBundle\Report;

use ClipBundle\Service\VraagDaoInterface;

class VragenPerLeeftijdscategorie extends AbstractReport
{
    protected $title = 'Vragen per leeftijdscategorie';

    protected $xPath = '';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    protected $xDescription = '';

    protected $yDescription = 'Leeftijdscategorie';

    protected $tables = [];

    public function __construct(VraagDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables[VraagDaoInterface::FASE_BEGINSTAND] = $this->dao->countByLeeftijdscategorie(VraagDaoInterface::FASE_BEGINSTAND, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_GESTART] = $this->dao->countByLeeftijdscategorie(VraagDaoInterface::FASE_GESTART, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_AFGESLOTEN] = $this->dao->countByLeeftijdscategorie(VraagDaoInterface::FASE_AFGESLOTEN, $this->startDate, $this->endDate);
        $this->tables[VraagDaoInterface::FASE_EINDSTAND] = $this->dao->countByLeeftijdscategorie(VraagDaoInterface::FASE_EINDSTAND, $this->startDate, $this->endDate);
    }
}
