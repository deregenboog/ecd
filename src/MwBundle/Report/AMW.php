<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use MwBundle\Service\KlantDao;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\LocatieDao;
use MwBundle\Service\MwDossierStatusDao;
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


    /**
     * @var Locaties die voor dit rapport gelden
     */
    private $locaties;

    /** @var LocatieDao */
    private $locatieDao;

    /** @var KlantDao  */
    private $klantDao;

    /** @var MwDossierStatusDao  */
    private $mdsDao;

    public function __construct(VerslagDao $dao, LocatieDao $locatieDao, KlantDao $klantDao, MwDossierStatusDao $mdsDao, $locaties)
    {
//        $this->locaties = $locaties;
        $this->dao = $dao;
        $this->locatieDao = $locatieDao;
        $this->mdsDao = $mdsDao;

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

        $this->resultAfsluitingen = $this->klantDao->findAllAfsluitredenenAfgeslotenKlantenForLocaties($this->startDate,$this->endDate,$this->locaties);

        $this->resultAanmeldingen = $this->mdsDao->findAllAanmeldingenForLocaties($this->startDate,$this->endDate,$this->locaties);
        $this->resultBinnenVia = $this->mdsDao->findAllAanmeldingenBinnenVia($this->startDate,$this->endDate,$this->locaties);

//        $this->resultDoorlooptijd = $this->bezoekerDao->getDoorlooptijdPerLocatie($this->startDate,$this->endDate,$this->locaties);

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
            'title' => "Aantal aanmeldingen per locatie",
            'yDescription' => "Locatie",
            'data' => $table->render(),
        ];
        return $report;
    }

    protected function buildBinnenVia()
    {
        $columns = [
            'Aantal aanmeldingen'=>'aantal',
//            'Afsluitreden'=>'naam',
        ];
        $table = new Grid($this->resultBinnenVia, $columns,"naam");
        $table
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYSort(false)
            ->setYTotals(true)
        ;

        $report = [
            'title' => "Aantal aanmeldingen per 'binnen via' optie",
            'yDescription' => "Binnen via",
            'data' => $table->render(),
        ];
        return $report;
    }

    protected function build()
    {

        $this->reports[] = $this->buildAantalKlantenVerslagenContactmomenten();
        $this->reports[] = $this->buildAfsluitingen();
        $this->reports[] = $this->buildAanmeldingen();
        $this->reports[] = $this->buildBinnenVia();
    }
}
