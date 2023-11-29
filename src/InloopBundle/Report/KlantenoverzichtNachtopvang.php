<?php

namespace InloopBundle\Report;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Klant;
use AppBundle\Report\AbstractReport;
use AppBundle\Report\Listing;
use Doctrine\ORM\EntityManager;
use InloopBundle\Entity\Locatie;

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

        return parent::setFilter($filter);
    }

    protected function init()
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

    protected function build()
    {
        foreach ($this->data as $title => $data) {
            $listing = new Listing($data, ['Naam' => 'naam', 'Roepnaam' => 'roepnaam','Geboortedatum'=> 'geboortedatum', 'Locatie'=>'locatienaam', 'Aantal registraties' => 'aantal']);
            $listing->setStartDate($this->startDate)->setEndDate($this->endDate);

            $this->reports[] = [
                'title' => $title,
                'data' => $listing->render(),
            ];
        }
    }
}
