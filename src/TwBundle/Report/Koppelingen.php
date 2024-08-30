<?php

namespace TwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use AppBundle\Report\Table;
use TwBundle\Service\HuurovereenkomstDaoInterface;
use TwBundle\Service\ProjectDaoInterface;

class Koppelingen extends AbstractReport
{
    protected $title = 'Koppelingen';

    protected $yPath = 'groep';

    protected $nPath = 'aantal';

    //    protected $xPath = 'ddd';

    protected $xDescription = 'Actieve koppelingen binnen de opgegeven periode';

    /**
     * @var ProjectDaoInterface
     */
    private $projectDao;

    private $koppelingenPerProjectData;

    private $columns = [
        'Actief' => 'aantalActief',
        'Gestart' => 'aantalGestart',
    ];

    public function __construct(HuurovereenkomstDaoInterface $dao, ProjectDaoInterface $projectDao)
    {
        $this->dao = $dao;
        $this->projectDao = $projectDao;
    }

    protected function init()
    {
        $this->koppelingenPerProjectData = $this->projectDao->countKoppelingenPerProject($this->startDate, $this->endDate);
        $this->tables['Koppelingen per Vorm van overeenkomst'] = $this->dao->countByVormvanovereenkomst($this->startDate, $this->endDate);
        $this->tables['Koppelingen per pandeigenaar'] = $this->dao->countByPandeigenaar($this->startDate, $this->endDate);
        $this->tables['Koppelingen per afsluitreden'] = $this->dao->countByAfsluitreden($this->startDate, $this->endDate);
        $this->tables['Koppelingen per stadsdeel'] = $this->dao->countByStadsdeel($this->startDate, $this->endDate);
        $this->tables['Koppelingen per woonplaats'] = $this->dao->countByPlaats($this->startDate, $this->endDate);
    }

    protected function build()
    {
        $this->buildProjectTable($this->koppelingenPerProjectData);
        parent::build();

        $this->reports[0]['yDescription'] = 'Project';
        $this->reports[1]['yDescription'] = 'VormVanOvereenkomst';
        $this->reports[2]['yDescription'] = 'Pandeigenaar';
        $this->reports[3]['yDescription'] = 'Afsluitreden';
        $this->reports[4]['yDescription'] = 'Stadsdeel';
        $this->reports[5]['yDescription'] = 'Woonplaats';
    }

    private function buildProjectTable($result)
    {
        $table = new Grid($result, $this->columns, 'groep');
        $table
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYSort(false)
            ->setYTotals(true);

        $data = $table->render();
        $report = [
            'title' => 'Koppelingen per project',
            'xDescription' => 'Koppelingen binnen de opgegeven periode',
            'yDescription' => $this->yDescription,
            'data' => $data,
        ];

        $this->reports[] = $report;
    }
}
