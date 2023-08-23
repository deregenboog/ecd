<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use MwBundle\Service\KlantDao;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\LocatieDao;
use MwBundle\Service\VerslagDao;

class AMW extends AbstractReport
{

    protected $title = 'AMW';

    protected $xPath = 'type';

//    protected $yPath = 'locatienaam';

    protected $nPath = 'aantal';



//    protected $yDescription = 'Locatienaam';


    protected $tables = [];

    /**
     * @var Locatie
     */
    private $locatie;


    private $amw_locaties;

    /** @var LocatieDao */
    private $locatieDao;

    /** @var KlantDao  */
    private $klantDao;

    public function __construct(VerslagDao $dao, LocatieDao $locatieDao, KlantDao $klantDao, $amw_locaties)
    {
//        $this->amw_locaties = $amw_locaties;
        $this->dao = $dao;
        $this->locatieDao = $locatieDao;

        $this->klantDao = $klantDao;

        $this->filterLocations($locatieDao->findAllActiveLocationsOfTypeMW());

    }

    private function filterLocations($allLocations)
    {
        /**
         * Filter: alles STED, alles Wchtlijst, alles Zonder Zorg, T6.
         */
        foreach($allLocations as $k=> $locatie)
        {
            $naam = $locatie->getNaam();
            if(strpos($naam, "Zonder ") !== false
                || strpos($naam,"T6") !== false
                || strpos($naam,"STED") !== false
                || strpos($naam,"Wachtlijst") !== false
            ) {
                //skip locatie
                continue;
            }
            $this->amw_locaties[] = $locatie->getNaam();
        }
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
            $this->amw_locaties);

        $this->resultKlantenVerslagen = $query->getResult();
        $this->resultKlantenVerslagenTotalUnique = $this->dao->getTotalUniqueKlantenForLocaties($this->startDate,$this->endDate,$this->amw_locaties);

        $this->resultAfsluitingen = $this->klantDao->findAllAfsluitredenenAfgeslotenKlanten($this->startDate,$this->endDate);

    }

    protected function buildAantalKlantenVerslagenContactmomenten()
    {
        $columns = [
            'Klanten'=>'aantalKlanten',
            'Verslagen'=>'aantalVerslagen',
            'Aantal contactmomenten'=>'aantalContactmomenten'
        ];

        $table = new Grid($this->resultKlantenVerslagen, $columns,"locatienaam");
        $table
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYSort(false)
            ->setYTotals(true)
        ;

        $report = [
            'title' => "Aantal klanten en verslagen",
//            'xDescription' => $this->xDescription,
            'yDescription' => "Locatienaam",
            'data' => $table->render(),
        ];

        foreach($columns as $k=>$c)
        {
            if(isset($this->resultKlantenVerslagenTotalUnique[$k]))
            {
                $report['data']['Uniek'][$c] = $this->resultKlantenVerslagenTotalUnique[$k];
            }
            else{
                $report['data']['Uniek'][$c] = "";
            }

        }

        return $report;
    }

    protected function buildAfsluitingen()
    {
        $columns = [
            'Aantal afsluitingen'=>'aantal',
//            'Afsluitreden'=>'naam',
        ];
        $table = new Grid($this->resultAfsluitingen, $columns,"naam");
        $table
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYSort(false)
            ->setYTotals(false)
        ;

        $report = [
            'title' => "Aantal afsluitingen per afsluitreden",
            'yDescription' => "Afsluitreden",
            'data' => $table->render(),
        ];
        return $report;
    }

    protected function build()
    {

        $this->reports[] = $this->buildAantalKlantenVerslagenContactmomenten();
        $this->reports[] = $this->buildAfsluitingen();
    }
}
