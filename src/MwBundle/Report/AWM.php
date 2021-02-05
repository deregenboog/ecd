<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use InloopBundle\Entity\Locatie;
use MwBundle\Service\VerslagDao;

class AWM extends AbstractReport
{

    protected $title = 'AWM';

    protected $xPath = 'type';

    protected $yPath = 'locatienaam';

    protected $nPath = 'aantal';

    protected $columns = [
        'Klanten'=>'aantal',
        'Verslagen'=>'aantalVerslagen'
        ];

    protected $yDescription = 'Locatienaam';


    protected $tables = [];

    /**
     * @var Locatie
     */
    private $locatie;

    public function __construct(VerslagDao $dao, $awm_locaties)
    {
        $this->awm_locaties = $awm_locaties;
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
            $this->awm_locaties
            //[31,36,37,38,42,43] //remote locations hardcoded...
//            [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21]
        );
//        $sql = $this->getFullSQL($query);
        $this->result = $query->getResult();

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

        $this->reports[] = [
            'title' => 'Economisch daklozen',
            'xDescription' => $this->xDescription,
            'yDescription' => $this->yDescription,
            'data' => $table->render(),
        ];
    }
}
