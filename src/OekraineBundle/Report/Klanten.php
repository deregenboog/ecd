<?php

namespace OekraineBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Service\BezoekerDao;
use OekraineBundle\Service\LocatieDao;
use OekraineBundle\Service\VerslagDao;

class Klanten extends AbstractReport
{

    protected $title = 'Klanten en verslagen';

    protected $xPath = 'type';

//    protected $yPath = 'locatienaam';

    protected $nPath = 'aantal';



//    protected $yDescription = 'Locatienaam';


    protected $tables = [];

    /**
     * @var Locatie
     */
    private $locatie;

    public $yLookupCollection;
    /**
     * @var Locaties die voor dit rapport gelden
     */
    private $locaties = [];

    /** @var LocatieDao */
    private $locatieDao;

    /** @var BezoekerDao  */
    private $bezoekerDao;

    public function __construct(VerslagDao $dao, LocatieDao $locatieDao, BezoekerDao $bezoekerDao)
    {
//        $this->locaties = $locaties;
        $this->dao = $dao;
        $this->locatieDao = $locatieDao;

        $this->bezoekerDao = $bezoekerDao;

        $this->filterLocations($locatieDao->findAll());

//        $this->yLookupCollection = new \stdClass();
    }

    private function filterLocations($allLocations)
    {
        /**
         * Filter: (gekopieerd van MW, laten staan voor evt later gebruik)
         */
        foreach($allLocations as $k=> $locatie)
        {
//            $naam = $locatie->getNaam();
//            if(strpos($naam, "Zonder ") !== false
//                || strpos($naam,"T6") !== false
//                || strpos($naam,"STED") !== false
//                || strpos($naam,"Wachtlijst") !== false
//            ) {
//                //skip locatie
//                continue;
//            }
            $this->locaties[] = $locatie->getNaam();
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
            $this->locaties);

        $this->resultKlantenVerslagen = $query->getResult();

        $this->resultKlantenVerslagenTotalUnique = $this->dao->getTotalUniqueKlantenForLocaties($this->startDate,$this->endDate,$this->locaties);

//        $this->resultAfsluitingen = $this->bezoekerDao->findAllAfsluitredenenAfgeslotenKlantenForLocaties($this->startDate,$this->endDate,$this->locaties);

//        $this->resultAanmeldingen = $this->bezoekerDao->findAllNieuweKlantenForLocaties($this->startDate,$this->endDate,$this->locaties);

//        $this->resultDoorlooptijd = $this->bezoekerDao->getDoorlooptijdPerLocatie($this->startDate,$this->endDate,$this->locaties);

    }

    protected function buildAantalKlantenVerslagenContactmomenten()
    {
        $columns = [
            'Klanten'=>'aantalKlanten',
            'Verslagen'=>'aantalVerslagen',
            'Aantal inloopverslagen'=>'aantalTypeInloop',
            'Aantal MW verslagen'=>'aantalTypeMW',
            'Aantal Mental Coach verslagen'=>'aantalTypePsych',
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
            'yLookupCollection' => ["Totaal" => "<b>Totaal</b>"],
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
            ->setYTotals(true)
        ;

        $report = [
            'title' => "Aantal afsluitingen per afsluitreden",
            'yDescription' => "Afsluitreden",
            'data' => $table->render(),
        ];
        return $report;
    }

    protected function buildAanmeldingen()
    {
        $columns = [
            'Aantal aanmeldingen'=>'aantal',
//            'Afsluitreden'=>'naam',
        ];
        $table = new Grid($this->resultAanmeldingen, $columns,"naam");
        $table
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYSort(false)
            ->setYTotals(true)
        ;

        $report = [
            'title' => "Aantal aanmeldingen per binnen via optie",
            'yDescription' => "Binnen via",
            'data' => $table->render(),
        ];
        return $report;
    }

    public function YLookupCollection($p = null)
    {
        return $p;
    }


    protected function build()
    {

        $this->reports[] = $this->buildAantalKlantenVerslagenContactmomenten();
//        $this->reports[] = $this->buildAfsluitingen();
//        $this->reports[] = $this->buildAanmeldingen();
    }
}
