<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use InloopBundle\Entity\Locatie;
use MwBundle\Service\VerslagDao;

class ZonderZorg extends AbstractReport
{

    protected $title = 'Zonder zorg';

    protected $xPath = 'type';

    protected $yPath = 'label';

//    protected $nPath = 'aantal';

    protected $columns = [
        'Klanten'=>'numContacten',
    ];

    protected $yDescription = 'Aantal contacten';

    protected $zonderzorg_locaties = [];

    protected $tables = [];

    protected $result;

    /**
     * @var Locatie
     */
    private $locatie;

    public function __construct(VerslagDao $dao, $zonderzorg_locaties)
    {
        $this->zonderzorg_locaties = $zonderzorg_locaties;
        $this->dao = $dao;
    }

    public function setFilter(array $filter)
    {
        if (array_key_exists('startdatum', $filter)) {
            $this->startDate = $filter['startdatum'];
        }

        if (array_key_exists('einddatum', $filter)) {
            $this->endDate = $filter['einddatum'];
        }

        return $this;
    }

    protected function init()
    {
        $result = $this->dao->countKlantenZonderZorg($this->startDate, $this->endDate, $this->zonderzorg_locaties);
//        $sql = $this->getFullSQL($query);
        $rows = $result->fetchAllAssociative();
//        foreach($rows as $row)
//        {
//
//            $d[] = ['Aantal'=> $row['numContacten']];
//        }
        $this->result = $rows;



    }

    protected function build()
    {

        $table = new Grid($this->result, $this->columns,$this->yPath);
        $table
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYSort(false)
            ->setYTotals(true)
        ;
         $report = [
            'title' => 'Zonder zorg (face-to-face)',
            'xDescription' => $this->xDescription,
            'yDescription' => $this->yDescription,
            'data' => $table->render(),
        ];


        $this->reports[] = $report;
    }
}
