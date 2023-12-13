<?php

namespace InloopBundle\Report;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Klant;
use AppBundle\Report\AbstractReport;
use AppBundle\Report\Listing;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\LocatieDao;

class KlantenoverzichtNachtopvang extends AbstractReport
{
    protected $title = 'Klantenoverzicht nachtopvang';

    /**
     * @var Locatie
     */
    protected $locatie;

    /**
     * @var Geslacht
     */
    protected $geslacht;

    protected $data = [];

    /**
     * @var EntityManager
     */
    private $entityManager;

    private $weekStartDate;
    private $weekEndDate;

    public function __construct(EntityManager $entityManager, LocatieDao $locatieDao)
    {
        $this->entityManager = $entityManager;
        $this->locatieDao = $locatieDao;
    }


    public function getFormOptions()
    {
        return [
            'enabled_filters' => [
                'startdatum',
                'einddatum',
                'locatie',
                'geslacht',
            ],
        ];
    }

    public function setFilter(array $filter)
    {
        if (array_key_exists('locatie', $filter)) {
            $this->locatie = $filter['locatie'];
        }

        if (array_key_exists('geslacht', $filter)) {
            $this->geslacht = $filter['geslacht'];
        }

         parent::setFilter($filter);
        $this->weekStartDate = $this->getFirstMondayOfWeek($this->startDate);
        $this->weekEndDate = clone $this->weekStartDate;
        $this->weekEndDate->modify("+6 days");

        $this->setEndDate($this->weekEndDate);
        $this->setStartDate($this->weekStartDate);

        return $filter;
    }

    protected function init_old()
    {
        $builder = $this->entityManager->getRepository(Klant::class)->createQueryBuilder('klant')
            ->select("CONCAT_WS(' ', klant.voornaam, klant.tussenvoegsel, klant.achternaam) AS naam, klant.roepnaam, klant.geboortedatum, locatie.naam AS locatienaam, COUNT(registratie.id) AS aantal")
            ->innerJoin('klant.registraties', 'registratie')
            ->innerJoin('registratie.locatie','locatie')
            ->innerJoin('locatie.locatieTypes','locatieTypes')
            ->where('DATE(registratie.binnen) BETWEEN :start_date AND :end_date')
            ->andWhere("locatieTypes.naam = 'Nachtopvang'")
            ->groupBy('klant.id, locatie.id')
            ->orderBy('klant.achternaam')
            ->setParameters([
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ])
        ;

        if ($this->locatie instanceof Locatie) {
            $builder
                ->andWhere('locatie = :locatie')
                ->setParameter('locatie', $this->locatie);
        }

        if ($this->geslacht instanceof Geslacht) {
            $builder
                ->andWhere('klant.geslacht = :geslacht')
                ->setParameter('geslacht', $this->geslacht);
        }

        $this->data[''] = $builder->getQuery()->getResult();
    }

    protected function init()
    {


        $locaties = $this->locatieDao->findAllActiveLocationsOfTypes(["Nachtopvang"]);

        foreach ($locaties as $locatie) {

            $builder = $this->entityManager->getRepository(Klant::class)->createQueryBuilder('klant')
                ->select(
                    "CONCAT_WS(' ', klant.voornaam, klant.tussenvoegsel, klant.achternaam) AS naam, 
                    klant.geboortedatum,
                    nationaliteit.naam AS nationaliteitnaam,
                    land.land AS landnaam,
                     SUM(CASE WHEN DAYOFWEEK(r.binnen) = 2 THEN 1 ELSE 0 END) as monday_count,
            SUM(CASE WHEN DAYOFWEEK(r.binnen) = 3 THEN 1 ELSE 0 END) as tuesday_count,
            SUM(CASE WHEN DAYOFWEEK(r.binnen) = 4 THEN 1 ELSE 0 END) as wednesday_count,
            SUM(CASE WHEN DAYOFWEEK(r.binnen) = 5 THEN 1 ELSE 0 END) as thursday_count,
            SUM(CASE WHEN DAYOFWEEK(r.binnen) = 6 THEN 1 ELSE 0 END) as friday_count,
            SUM(CASE WHEN DAYOFWEEK(r.binnen) = 7 THEN 1 ELSE 0 END) as saturday_count,
            SUM(CASE WHEN DAYOFWEEK(r.binnen) = 1 THEN 1 ELSE 0 END) as sunday_count"
                )
                ->innerJoin('klant.registraties', 'r')
                ->innerJoin('klant.land', 'land')
                ->innerJoin('klant.nationaliteit', 'nationaliteit')
                ->leftJoin('r.locatie','locatie')
                ->where('r.binnen BETWEEN :start_date AND :end_date')
                ->andWhere("locatie.naam = :locatienaam")
                ->groupBy('klant.id')
                ->orderBy('klant.achternaam')
                ->setParameters([
                    'start_date' => $this->weekStartDate,
                    'end_date' => $this->weekEndDate,
                    "locatienaam"=>$locatie->getNaam(),
                ]);
            
            $this->data[$locatie->getNaam()][] = $builder->getQuery()->getResult();
        }

    }

    protected function build()
    {

        foreach($this->data as $locatieNaam=>$locatieData)
        {
            $tmpDate = clone ($this->weekStartDate);
            foreach ($locatieData as $title => $data) {
                $listing = new Listing($data, ['Naam' => 'naam',
                        'Geboortedatum'=> 'geboortedatum',
//                        'Geboorteland'=> 'landnaam',
                        'Nationaliteit'=> 'nationaliteitnaam',
                        "Aantal maandag ({$tmpDate->format("d-m")})" => "monday_count",
                        "Aantal dinsdag ({$tmpDate->modify("+1 day")->format("d-m")})" => "tuesday_count",
                        "Aantal woensdag ({$tmpDate->modify("+1 day")->format("d-m")})" => "wednesday_count",
                        "Aantal donderdag ({$tmpDate->modify("+1 day")->format("d-m")})" => "thursday_count",
                        "Aantal vrijdag ({$tmpDate->modify("+1 day")->format("d-m")})" => "friday_count",
                        "Aantal zaterdag ({$tmpDate->modify("+1 day")->format("d-m")})" => "saturday_count",
                        "Aantal zondag ({$tmpDate->modify("+1 day")->format("d-m")})" => "sunday_count",
                    ]
                );
                $listing->setStartDate($this->weekStartDate)->setEndDate($this->weekEndDate);

                $this->reports[] = [
                    'title' => $locatieNaam,
                    'data' => $listing->render(),
                ];
            }
        }

    }

    protected function getFirstMondayOfWeek(\DateTime $dateTime): \DateTime {
//        $dateTime = new \DateTime("2023-12-03");

        // Set the time to midnight to avoid issues with time
        $dateTime->setTime(0, 0, 1);

        // Get the current day of the week 0 = sunday.
        $d = $dateTime->format('l');
        $currentDayOfWeek = $dateTime->format('w');

        // Calculate the difference between the current day and Monday (1 for Monday, 2 for Tuesday, etc.)
        $daysToAdd = ($currentDayOfWeek == 1) ? 0 : (1 - $currentDayOfWeek);

        // Modify the date to get the (first) Monday of the week
        $dateTime->modify("+$daysToAdd days");

        return $dateTime;
    }
}
