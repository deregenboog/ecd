<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use AppBundle\Entity\Locatie;
use MwBundle\Service\VerslagDao;

class EconomischDaklozen extends AbstractReport
{

    protected $title = 'Economisch daklozen';

    protected $xPath = 'type';

    protected $yPath = 'locatienaam';

    protected $nPath = 'aantal';

    protected $columns = [
        'Klanten'=>'aantal',
        'Aantal contactmomenten'=>'aantalContactmomenten',
        ];

    protected $yDescription = 'Locatienaam';


    protected $tables = [];

    /**
     * @var Locatie
     */
    private $locatie;

    public function __construct(VerslagDao $dao, $economischDaklozenLocaties)
    {
        $this->economisch_daklozen_locaties = $economischDaklozenLocaties;
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
        $query = $this->dao->countUniqueKlantenVoorLocaties(
            $this->startDate,
            $this->endDate,
            $this->economisch_daklozen_locaties
            //[31,36,37,38,42,43] //remote locations hardcoded...
//            [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21]
        );
//        $sql = $this->getFullSQL($query);
        $this->result = $query->getResult();
        $this->resultUnique = $this->dao->getTotalUniqueKlantenForLocaties($this->startDate,$this->endDate,$this->economisch_daklozen_locaties);


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
            'title' => 'Economisch daklozen',
            'xDescription' => $this->xDescription,
            'yDescription' => $this->yDescription,
            'data' => $table->render(),
        ];
         //$report['data']['Totaal'];
        foreach($this->columns as $k=>$c)
        {
            if(isset($this->resultUnique[$k]))
            {
                $report['data']['Uniek'][$c] = $this->resultUnique[$k];
            }
            else{
                $report['data']['Uniek'][$c] = "";
            }

        }

        $this->reports[] = $report;
    }
}
