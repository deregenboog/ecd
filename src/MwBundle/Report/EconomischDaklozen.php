<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use InloopBundle\Entity\Locatie;
use MwBundle\Service\VerslagDao;

class EconomischDaklozen extends AbstractReport
{
    protected $title = 'Economisch daklozen (oud, archief)';

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

    private $result;
    private $resultUnique;
    private $economischDaklozenLocaties;

    public function __construct(VerslagDao $dao, $economischDaklozenLocaties)
    {
        $this->economischDaklozenLocaties = $economischDaklozenLocaties;
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

        $this->result = $this->dao->countUniqueKlantenVoorLocaties(
            $this->startDate,
            $this->endDate,
            $this->economischDaklozenLocaties
        );
        $this->resultUnique = $this->dao->getTotalUniqueKlantenForLocaties($this->startDate, $this->endDate, $this->economischDaklozenLocaties);
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
