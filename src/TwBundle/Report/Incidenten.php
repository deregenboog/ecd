<?php

namespace TwBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Listing;
use Doctrine\ORM\EntityManagerInterface;
use TwBundle\Entity\Incident;

class Incidenten extends AbstractReport
{
    protected $title = 'Incidenten';

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
            ],
        ];
    }

    public function setFilter(array $filter)
    {
        return parent::setFilter($filter);
    }

    protected function init()
    {
        $builder = $this->entityManager->getRepository(Incident::class)->createQueryBuilder('incident')
            ->select('COUNT(incident.id) AS incidenten,
            SUM(incident.politie) AS politie,
            SUM(incident.ambulance) AS ambulance,
            SUM(incident.crisisdienst) AS crisisdienst
        ')
            ->where('DATE(incident.datum) BETWEEN :start_date AND :end_date')
            ->setParameters([
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ]);
        $this->data = $builder->getQuery()->getResult();
    }

    protected function build()
    {
        $listing = new Listing($this->data, [ 'Aantal incidenten' => 'incidenten', 'Politie' => 'politie', 'Ambulance' => 'ambulance', 'Crisisdienst' => 'crisisdienst']);
        $listing->setStartDate($this->startDate)->setEndDate($this->endDate);

        $data['Totaal'] = $listing->render()[1];
        $this->reports[] = [
            'title' => 'Incidenten',
            'data' => $data,
        ];
    }
}
