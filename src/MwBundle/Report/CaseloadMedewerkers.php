<?php

namespace MwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Grid;
use InloopBundle\Service\LocatieDao;
use MwBundle\Service\KlantDao;
use MwBundle\Service\MwDossierStatusDao;
use MwBundle\Service\VerslagDao;

class CaseloadMedewerkers extends AbstractReport
{
    protected $title = 'Contactmomenten per medewerker';

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

    /** @var MwDossierStatusDao */
    protected $mdsDao;

    private $actieveKlanten;

    private $resultContactmomentenPerMedewerker;

    public function __construct(VerslagDao $dao, LocatieDao $locatieDao, KlantDao $klantDao, MwDossierStatusDao $mdsDao)
    {
        $this->dao = $dao;
        $this->locatieDao = $locatieDao;
        $this->mdsDao = $mdsDao;

        $this->klantDao = $klantDao;

        //        $this->filterLocations($locatieDao->findAllActiveLocationsOfTypeMW());
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
        // Haal klantenIds op die actief waren in de periode. Dus ze waren actief en zijn afgesloten, of ze zijn aangemeld en weer afgesloten, of nog niet afgesloten.
        // Bruikbaar in andere queries.
        $this->actieveKlanten = $this->mdsDao->getActiveKlantIdsForPeriod($this->startDate, $this->endDate);

        $this->resultContactmomentenPerMedewerker = $this->dao->countContactmomentenPerMedewerker($this->startDate, $this->endDate, $this->actieveKlanten);
    }

    protected function buildAantalContactmomentenPerMedewerker($data, $titel)
    {
        $columns = [
            'Aantal contactmomenten' => 'aantal',
            'Aantal verslagen' => 'aantalVerslagen',
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
            'title' => $titel,
            'yDescription' => 'Medewerker',
            'data' => $table->render(),
        ];

        return $report;
    }

    protected function build()
    {
        $this->reports[] = $this->buildAantalContactmomentenPerMedewerker($this->resultContactmomentenPerMedewerker, 'Aantal verslagen en contactmomenten per medewerker');
    }
}
