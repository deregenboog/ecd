<?php

namespace VillaBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use VillaBundle\Service\SlaperDaoInterface;

class Slapers extends AbstractReport
{
    protected $title = 'Slapers';

    protected $xPath = 'type';
    protected $nPath = 'aantal';
    protected $xDescription = 'Type slaper';

    protected $overnachtingenTable;
    protected $slapersTable;

    public function __construct(SlaperDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->overnachtingenTable = $this->dao->countOvernachtingenByType($this->startDate, $this->endDate);
        $this->slapersTable = $this->dao->countSlapersByType($this->startDate, $this->endDate);
    }

    protected function build()
    {
        // Create table for overnachtingen
        $overnachtingenTable = new Table($this->overnachtingenTable, $this->xPath, null, $this->nPath);
        $overnachtingenTable->setStartDate($this->startDate)->setEndDate($this->endDate);

        // Create table for unique slapers
        $slapersTable = new Table($this->slapersTable, $this->xPath, null, $this->nPath);
        $slapersTable->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports = [
            [
                'title' => 'Aantal overnachtingen per type',
                'xDescription' => $this->xDescription,
                'data' => $overnachtingenTable->render(),
            ],
            [
                'title' => 'Aantal unieke slapers per type',
                'xDescription' => $this->xDescription,
                'data' => $slapersTable->render(),
            ],
        ];
    }
}