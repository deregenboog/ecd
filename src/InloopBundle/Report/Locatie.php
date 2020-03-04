<?php

namespace InloopBundle\Report;

use AppBundle\Entity\Geslacht;
use AppBundle\Form\Model\AppDateRangeModel;
use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use Doctrine\ORM\EntityManagerInterface;
use InloopBundle\Entity;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Registratie;
use InloopBundle\Entity\Schorsing;

class Locatie extends AbstractReport
{
    protected $title = 'Locatierapportage';

    /**
     * @var Entity\Locatie
     */
    protected $locatie;

    /**
     * @var Geslacht
     */
    protected $geslacht;

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
                'locatie',
                'startdatum',
                'einddatum',
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
        list($count, $unique_per_location) = $this->getData();

        $this->reports[] = [
            'title' => 'Basisstatistieken',
            'data' => [
                'Totaal aantal registraties (bezoeken)' => ['Aantal' => $count['totalVisits']],
                'Aantal schorsingen' => ['Aantal' => $count['suspensions']],
                'Nieuwe klanten met een registratie' => ['Aantal' => $count['new_clients']],
                'Nieuwe intakes' => ['Aantal' => $count['intakes']],
                'Unieke bezoekers' => ['Aantal' => $count['unique_visitors']],
                'Unieke bezoekers met 4+ bezoeken in deze periode' => ['Aantal' => $count['unique_visitors_4_or_more_visits']],
            ],
        ];

        $this->reports[] = [
            'title' => 'Faciliteiten',
            'data' => [
                'Maaltijden' => ['Aantal' => $count['meals']],
                'Kleding' => ['Aantal' => $count['clothes']],
                'Douches' => ['Aantal' => $count['shower']],
                'Activering' => ['Aantal' => $count['activation']],
            ],
        ];

        $table = new Table($unique_per_location, null, 'naam', 'cnt');
        $table->setStartDate($this->startDate)->setEndDate($this->endDate)->setXTotalLabel('Aantal');
        $this->reports[] = [
            'title' => 'Unieke geregistreerde bezoekers per locatie',
            'data' => $table->render(),
        ];
    }

    protected function build()
    {
        return;
    }

    /**
     * These queries are copied from the original ECD-codebase.
     *
     * @todo refactor this and use Doctrine ORM instead of raw SQL.
     *
     * @return array
     */
    private function getData()
    {
        $endDatePlusOneDay = (clone $this->endDate)->modify('+1 day');

        $where = " binnen >= '{$this->startDate->format('Y-m-d')}' AND binnen < '{$endDatePlusOneDay->format('Y-m-d')}' ";
        if ($this->locatie instanceof Entity\Locatie) {
            $where .= " AND locatie_id = {$this->locatie->getId()} ";
        }
        if ($this->geslacht instanceof Geslacht) {
            $where .= " AND geslacht_id = {$this->geslacht->getId()} ";
        }

        $sql = "CREATE TEMPORARY TABLE tmp_registrations
            SELECT k.id AS klant_id, voornaam, tussenvoegsel, achternaam, douche, kleding, maaltijd, activering, locatie_id, k.created, binnen
            FROM klanten k
            JOIN registraties r ON r.klant_id = k.id
            WHERE {$where} ";
        $this->entityManager->getConnection()->executeQuery($sql);

        $registratieRepository = $this->entityManager->getRepository(Registratie::class);
        $builder = $registratieRepository->createQueryBuilder('registratie')
            ->select('COUNT(DISTINCT registratie.klant) AS cnt')
            ->where('registratie.binnen >= :start_date AND registratie.binnen < :end_date')
            ->setParameters([
                'start_date' => $this->startDate,
                'end_date' => $endDatePlusOneDay,
            ])
        ;
        $count['uniqueVisits'] = $builder->getQuery()->getSingleScalarResult();

        $count['totalVisits'] = $this->entityManager->getConnection()->fetchColumn('SELECT COUNT(*) AS cnt FROM tmp_registrations');

        $sql = 'SELECT
                SUM(ABS(douche)) AS douche,
                SUM(kleding) AS kleding,
                SUM(maaltijd) AS maaltijd,
                SUM(activering) AS activering
            FROM tmp_registrations';
        $r = $this->entityManager->getConnection()->fetchAll($sql);
        $count['shower'] = $r[0]['douche'];
        $count['clothes'] = $r[0]['kleding'];
        $count['meals'] = $r[0]['maaltijd'];
        $count['activation'] = $r[0]['activering'];

        $schorsingRepository = $this->entityManager->getRepository(Schorsing::class);
        $builder = $schorsingRepository->createQueryBuilder('schorsing')->select('COUNT(schorsing.id)');
        $dateRange = new AppDateRangeModel($this->startDate, $this->endDate);
        $schorsingRepository->filterByDateRange($builder, $dateRange);
        if ($this->locatie instanceof Entity\Locatie) {
            $schorsingRepository->filterByLocatie($builder, $this->locatie);
        }
        if ($this->geslacht instanceof Geslacht) {
            $schorsingRepository->filterByGeslacht($builder, $this->geslacht);
        }
        $count['suspensions'] = $builder->getQuery()->getSingleScalarResult();

        $intakeRepository = $this->entityManager->getRepository(Intake::class);
        $builder = $intakeRepository->createQueryBuilder('intake')->select('COUNT(intake.id)')
            ->where('intake.created >= :start_date')
            ->andWhere('intake.created < :end_date')
            ->setParameters([
                'start_date' => $this->startDate,
                'end_date' => $endDatePlusOneDay,
            ])
        ;

        if ($this->locatie instanceof Entity\Locatie) {
            $builder
                ->andWhere('intake.intakelocatie = :locatie OR intake.gebruikersruimte = :locatie OR intake.locatie3 = :locatie')
                ->setParameter('locatie', $this->locatie)
            ;
        }
        if ($this->geslacht instanceof Geslacht) {
            $builder
                ->innerJoin('intake.klant', 'klant', 'WITH', 'klant.geslacht = :geslacht')
                ->setParameter('geslacht', $this->geslacht)
            ;
        }
        $count['intakes'] = $builder->getQuery()->getSingleScalarResult();

        $sql = 'SELECT COUNT(distinct klant_id) AS cnt FROM tmp_registrations ';
        $count['unique_visitors'] = $this->entityManager->getConnection()->fetchColumn($sql);

        $sql = 'SELECT COUNT(*) AS cnt FROM (SELECT COUNT(*) AS cnt FROM tmp_registrations GROUP BY klant_id HAVING cnt >= 4) AS subq';
        $count['unique_visitors_4_or_more_visits'] = $this->entityManager->getConnection()->fetchColumn($sql);

        $sql = "SELECT COUNT(*) AS cnt
            FROM (
                SELECT klant_id FROM tmp_registrations
                WHERE created >= '{$this->startDate->format('Y-m-d')}' AND created < '{$endDatePlusOneDay->format('Y-m-d')}'
                GROUP BY klant_id
            ) AS subq ";
        $count['new_clients'] = $this->entityManager->getConnection()->fetchColumn($sql);

        $sql = 'SELECT naam, COUNT(*) AS cnt
            FROM (
                SELECT klant_id, locatie_id, COUNT(*) AS cnt
                FROM tmp_registrations
                GROUP BY klant_id, locatie_id
            ) AS subq
            JOIN locaties l ON l.id = locatie_id
            GROUP BY locatie_id';

        $unique_per_location = $this->entityManager->getConnection()->fetchAll($sql);

        return [$count, $unique_per_location];
    }
}
