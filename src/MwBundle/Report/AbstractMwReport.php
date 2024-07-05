<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use InloopBundle\Service\LocatieDao;
use MwBundle\Service\DossierStatusDao;
use MwBundle\Service\KlantDao;
use MwBundle\Service\VerslagDao;

abstract class AbstractMwReport extends AbstractReport
{
    protected $title = '';

    protected $xPath = 'type';

    //    protected $yPath = 'locatienaam';

    protected $nPath = 'aantal';

    //    protected $yDescription = 'Locatienaam';

    protected $tables = [];

    /**
     * Locaties die voor dit rapport gelden.
     */
    protected $locaties;

    /** @var LocatieDao */
    protected $locatieDao;

    /** @var KlantDao */
    protected $klantDao;

    /** @var DossierStatusDao */
    protected $mdsDao;

    protected $actieveKlanten;
    protected $resultKlantenVerslagen;
    protected $resultKlantenVerslagenWOActief;
    protected $resultKlantenVerslagenTotalUnique;
    protected $resultKlantenVerslagenTotalUniqueWOActief;
    protected $resultAanmeldingen;
    protected $resultBinnenVia;
    protected $resultAfsluitingen;
    protected $resultDoorlooptijd;

    public function __construct(VerslagDao $dao, LocatieDao $locatieDao, KlantDao $klantDao, DossierStatusDao $mdsDao, $locaties = [])
    {
        $this->locaties = $locaties;
        $this->dao = $dao;
        $this->locatieDao = $locatieDao;
        $this->mdsDao = $mdsDao;

        $this->klantDao = $klantDao;

        $this->filterLocations($locatieDao->findAllActiveLocationsOfTypeMW());
    }

    abstract protected function filterLocations($allLocations);

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
        // Haal klantenIds op die actief waren in de periode. Dus ze waren actief en zijn afgesloten, of ze zijn aangemeld en weer afgesloten, of nog niet afgesloten.
        // Bruikbaar in andere queries.
        $this->actieveKlanten = $this->mdsDao->getActiveKlantIdsForPeriod($this->startDate, $this->endDate);

        /*
         * Oude manier van rapporteren is om verslagen te tellen. Dit hield geen rekening met dossierstatus.
         * Daarom actieveKlanten toegevoegd.
         */
        $this->resultKlantenVerslagen = $this->dao->countUniqueKlantenVoorLocaties($this->startDate, $this->endDate, $this->locaties, $this->actieveKlanten);
        $this->resultKlantenVerslagenTotalUnique = $this->dao->getTotalUniqueKlantenForLocaties($this->startDate, $this->endDate, $this->locaties, $this->actieveKlanten);

        /*
         * Om aansluiting te houden bij het verleden ook dezelfde query als vroeger maar dan nu zonder actieveKlanten.
         */
        $this->resultKlantenVerslagenWOActief = $this->dao->countUniqueKlantenVoorLocaties($this->startDate, $this->endDate, $this->locaties);
        $this->resultKlantenVerslagenTotalUniqueWOActief = $this->dao->getTotalUniqueKlantenForLocaties($this->startDate, $this->endDate, $this->locaties);

        $this->resultAanmeldingen = $this->mdsDao->findAllAanmeldingenForLocaties($this->startDate, $this->endDate, $this->locaties);
        $this->resultBinnenVia = $this->mdsDao->findAllAanmeldingenBinnenVia($this->startDate, $this->endDate, $this->locaties);

        $this->resultAfsluitingen = $this->mdsDao->findAllAfsluitredenenAfgeslotenKlantenForLocaties($this->startDate, $this->endDate, $this->locaties);
        $this->resultDoorlooptijd = $this->mdsDao->findDoorlooptijdForLocaties($this->startDate, $this->endDate, $this->locaties);
    }

    protected function buildAantalKlantenVerslagenContactmomenten($data, $total, $titel, $columns = [])
    {
        if (count($columns) < 1) {
            $columns = [
                'Klanten' => 'aantalKlanten',
                'Verslagen' => 'aantalVerslagen',
                'Aantal contactmomenten' => 'aantalContactmomenten',
                'Inloopverslagen' => 'aantalInloop',
                'MW verslagen' => 'aantalMw',
            ];
        }

        $table = new Grid($data, $columns, 'locatienaam');
        $table
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYSort(false)
            ->setYTotals(true)
        ;

        $report = [
            'title' => $titel,
//            'xDescription' => $this->xDescription,
            'yDescription' => 'Locatienaam',
            'data' => $table->render(),
        ];

        foreach ($columns as $k => $c) {
            if (isset($total[$c])) {
                $report['data']['Uniek'][$c] = $total[$c];
            } else {
                $report['data']['Uniek'][$c] = '';
            }
        }

        return $report;
    }

    protected function buildAfsluitingen($data)
    {
        $columns = [
            'Aantal afsluitingen' => 'aantal',
//            'Afsluitreden'=>'naam',
        ];
        $table = new Grid($data, $columns, 'naam');
        $table
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYSort(false)
            ->setYTotals(true)
        ;

        $report = [
            'title' => 'Aantal afsluitingen per afsluitreden',
            'yDescription' => 'Afsluitreden',
            'data' => $table->render(),
        ];

        return $report;
    }

    protected function buildAanmeldingen($data)
    {
        $columns = [
            'Aantal aanmeldingen' => 'aantal',
//            'Afsluitreden'=>'naam',
        ];
        $table = new Grid($data, $columns, 'naam');
        $table
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYSort(false)
            ->setYTotals(true)
        ;

        $report = [
            'title' => 'Aantal aanmeldingen per locatie',
            'yDescription' => 'Locatie',
            'data' => $table->render(),
        ];

        return $report;
    }

    protected function buildBinnenVia($data)
    {
        $columns = [
            'Aantal aanmeldingen' => 'aantal',
        ];
        $table = new Grid($data, $columns, 'naam');
        $table
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYSort(false)
            ->setYTotals(true)
        ;

        $report = [
            'title' => "Aantal aanmeldingen per 'binnen via' optie",
            'yDescription' => 'Binnen via',
            'data' => $table->render(),
        ];

        return $report;
    }

    protected function buildDoorlooptijd($data)
    {
        $columns = [
            'Aantal afsluitingen' => 'aantal',
            'Gemiddelde doorlooptijd (dagen)' => 'avg_duration',
        ];
        $table = new Grid($data, $columns, 'naam');
        $table
            ->setStartDate($this->startDate)
            ->setEndDate($this->endDate)
            ->setYSort(false)
            ->setYTotals(true)
        ;

        $report = [
            'title' => 'Gemiddelde doorlooptijd per afsluitreden',
            'yDescription' => 'Doorloptijd',
            'data' => $table->render(),
        ];

        return $report;
    }

    protected function build()
    {
        //        $this->reports[] = $this->buildAantalKlantenVerslagenContactmomenten($this->resultKlantenVerslagen,$this->resultKlantenVerslagenTotalUnique,'Aantal verslagen en contactmomenten van klanten die actief waren in de periode');
        $this->reports[] = $this->buildAantalKlantenVerslagenContactmomenten($this->resultKlantenVerslagenWOActief, $this->resultKlantenVerslagenTotalUniqueWOActief, 'Aantal verslagen en contactmomenten');
        $this->reports[] = $this->buildAanmeldingen($this->resultAanmeldingen);
        $this->reports[] = $this->buildBinnenVia($this->resultBinnenVia);
        $this->reports[] = $this->buildAfsluitingen($this->resultAfsluitingen);
        $this->reports[] = $this->buildDoorlooptijd($this->resultDoorlooptijd);
    }
}
