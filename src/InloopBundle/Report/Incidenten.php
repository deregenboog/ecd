<?php

namespace InloopBundle\Report;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Klant;
use AppBundle\Report\AbstractReport;
use AppBundle\Report\Listing;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Incident;
use InloopBundle\Entity\Locatie;

class Incidenten extends AbstractReport
{
    protected $title = 'Incidenten';

    /**
     * @var Locatie
     */
    protected $locatie;


    protected $data = [];

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFormOptions()
    {
        return [
            'enabled_filters' => [
                'startdatum',
                'einddatum',
                'locatie',
            ],
        ];
    }

    public function setFilter(array $filter)
    {
        if (array_key_exists('locatie', $filter)) {
            $this->locatie = $filter['locatie'];
        }

        return parent::setFilter($filter);
    }

    protected function init()
    {
        $builder = $this->entityManager->getRepository(Incident::class)->createQueryBuilder('incident')
            ->select("SUM(incident.politie) AS politie, SUM(incident.ambulance) AS ambulance, SUM(incident.crisisdienst) as crisisdienst, locatie.naam AS locatienaam")
            ->innerJoin('incident.locatie', 'locatie')
            ->where('DATE(incident.datum) BETWEEN :start_date AND :end_date')
            ->groupBy("incident.locatie")
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


        $this->data = $builder->getQuery()->getResult();
    }

    protected function build()
    {
        $totals = [
            'locatienaam'=>'Totaal',
            'politie'=>0,
            'ambulance'=>0,
            'crisisdienst'=>0,
        ];
        foreach($this->data as $i)
        {
            $totals['politie'] += $i['politie'];
            $totals['ambulance'] += $i['ambulance'];
            $totals['crisisdienst'] += $i['crisisdienst'];
        }
        $this->data[] = $totals;

        $listing = new Listing($this->data, ['Locatie'=>'locatienaam','Politie' => 'politie', 'Ambulance' => 'ambulance', 'Crisisdienst' => 'crisisdienst']);
        $listing->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports[] = [
            'title' => "Incidenten",
            'data' => $listing->render(),
        ];

    }
}
