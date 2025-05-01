<?php

namespace AppBundle\Report;

use AppBundle\Report\AbstractReport;
use AppBundle\Report\Listing;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Incident;

use function PHPSTORM_META\type;

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
        $builder = $this->entityManager
            ->getRepository(Incident::class)
            ->createQueryBuilder('incident')
            ->where('DATE(incident.datum) BETWEEN :start_date AND :end_date')
            ->setParameters([
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ]);
        $this->data = $builder->getQuery()->getResult();
    }

    protected function build()
    {
        $report = [];
        $total = [
            'incidenten' => count($this->data),
            'politie' => 0,
            'ambulance' => 0,
            'crisisdienst' => 0,
        ];
        $index = 0;
        foreach ($this->data as $i) {
            $row = $report[$this->getRowTitle($i)] ?? [
                'incidenten' => 0,
                'politie' => 0,
                'ambulance' => 0,
                'crisisdienst' => 0,
            ];
            $row['incidenten']++;

            if ($i->isPolitie()) {
                $row['politie']++;
                $total['politie']++;
            }
            if ($i->isAmbulance()) {
                $row['ambulance']++;
                $total['ambulance']++;
            }
            if ($i->isCrisisdienst()) {
                $row['crisisdienst']++;
                $total['crisisdienst']++;
            }

            $report[$this->getRowTitle($i)] = $row;
        }

        $report['Totaal'] = $total;

        $listing = new Listing($this->data, ['Aantal incidenten' => 'incidenten', 'Politie' => 'politie', 'Ambulance' => 'ambulance', 'Crisisdienst' => 'crisisdienst']);
        $listing->setStartDate($this->startDate)->setEndDate($this->endDate);

        $this->reports[] = [
            'title' => 'Incidenten',
            'data' => $report,
        ];
    }

    function getRowTitle($i): string
    {
        $labels = [
            'hs_dienstverleners' => 'Homeservice Incidenten',
            'dagbesteding' => 'Dagbesteding Incidenten',
            'app' => 'Alle Incidenten',
            'inloop' => 'Inloophuizen Incidenten',
            'mw' => 'Maatschappelijk Werk Incidenten',
            'iz' => 'Informele Zorg Incidenten',
            'tw' => 'Tijdelijk Wonen Incidenten',
            'villa' => 'Villa Incidenten',
        ];
        return $labels[$i->getIncidentType()];
    }
}
