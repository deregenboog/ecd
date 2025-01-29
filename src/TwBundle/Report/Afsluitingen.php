<?php

namespace TwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use TwBundle\Service\HuurderAfsluitingDao;
use TwBundle\Service\HuurovereenkomstAfsluitingDaoInterface;

class Afsluitingen extends AbstractReport
{
    protected $title = 'Afsluitingen';

    protected $xPath = 'projectnaam';

    protected $yPath = 'afsluitreden';

    protected $nPath = 'aantal';

    protected $xDescription = 'Project';

    protected $yDescription = 'Afsluitreden huurovereenkomst';

    private $afsluitredenenPerKlant = [];

    public function __construct(HuurovereenkomstAfsluitingDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    protected function init()
    {
        $this->tables['Afsluitredenen huurovereenkomst per project'] = $this->dao->countByProject($this->startDate, $this->endDate);
        $this->afsluitredenenPerKlant = $this->dao->countByKlant($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $this->buildAantalPerKlant($this->afsluitredenenPerKlant);
        parent::build();
    }

    private function buildAantalPerKlant($result)
    {
        $table = new Grid($result, ['Totaal' => "aantal"], 'afsluitreden');
        $table
            ->setYSort(true)
            ->setYTotals(true)
        ;
        
        $data = $table->render();
        
        $report = [
            'title' => 'Aantal klanten per afsluitreden',
            'data' => $data,
        ];

        $this->reports[] = $report;
    }
}
