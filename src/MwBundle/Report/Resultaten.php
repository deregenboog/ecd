<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use MwBundle\Entity\Klant;
use MwBundle\Service\KlantDao;

class Resultaten extends AbstractReport
{
    protected $title = 'Resultaten';

    protected $xPath = 'type';

    protected $yPath = 'locatienaam';

    protected $nPath = 'aantal';

    protected $columns = [
        'Klanten'=>'aantal',
        'Verslagen'=>'aantalVerslagen'
    ];

    protected $yDescription = 'Locatienaam';

    protected $tables = [];

    private $result;

    public function __construct(KlantDao $dao)
    {
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
            $this->endDate
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
