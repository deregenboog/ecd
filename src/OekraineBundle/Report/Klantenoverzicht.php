<?php

namespace OekraineBundle\Report;

use AppBundle\Entity\Geslacht;
use AppBundle\Report\AbstractReport;
use AppBundle\Report\Listing;
use Doctrine\ORM\EntityManagerInterface;
use OekraineBundle\Entity\Bezoeker;
use OekraineBundle\Entity\Locatie;

class Klantenoverzicht extends AbstractReport
{
    protected $title = 'Klantenoverzicht';

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
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
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
        $builder = $this->entityManager->getRepository(Bezoeker::class)->createQueryBuilder('bezoeker')
            ->select("CONCAT_WS(' ', klant.voornaam, klant.tussenvoegsel, klant.achternaam) AS naam, klant.roepnaam, klant.geboortedatum, COUNT(registratie.id) AS aantal")
            ->innerJoin('bezoeker.appKlant', 'klant')
            ->innerJoin('bezoeker.registraties', 'registratie')
            ->where('DATE(registratie.binnen) BETWEEN :start_date AND :end_date')
            ->groupBy('bezoeker.id')
            ->orderBy('klant.achternaam')
            ->setParameters([
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ])
        ;

        if ($this->locatie instanceof Locatie) {
            $builder
                ->andWhere('registratie.locatie = :locatie')
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
            $listing = new Listing($data, ['Naam' => 'naam', 'Roepnaam' => 'roepnaam', 'Aantal registraties' => 'aantal']);
            $listing->setStartDate($this->startDate)->setEndDate($this->endDate);

            $this->reports[] = [
                'title' => $title,
                'data' => $listing->render(),
            ];
        }
    }
}
