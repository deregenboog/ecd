<?php

namespace InloopBundle\Report;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Land;
use AppBundle\Report\AbstractReport;
use AppBundle\Report\Table;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Registratie;
use InloopBundle\Entity\Verslaving;
use MwBundle\Entity\Doorverwijzing;
use MwBundle\Entity\Verslag;
use MwBundle\Entity\Verslaginventarisatie;

class Infobalie extends AbstractReport
{
    protected $title = 'EU burgers';

    /**
     * @var Land[]
     */
    protected $landen;

    /**
     * @var Locatie
     */
    protected $locatie;

    /**
     * @var Geslacht
     */
    protected $geslacht;

    /**
     * @var int
     */
    protected $referentieperiode;

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
                'referentieperiode',
                'amoc_landen',
            ],
        ];
    }

    public function setFilter(array $filter)
    {
        if (array_key_exists('locatie', $filter)) {
            $this->locatie = $filter['locatie'];

            $builder = $this->entityManager->getRepository(Locatie::class)->createQueryBuilder('locatie')
                ->select('locatie.id')
            ;


            $locatie_lijst = array_map(function ($row) {
                return (int)$row['id'];
            }, $builder->getQuery()->getScalarResult());
            $this->locatie = implode(",",$locatie_lijst);
            $this->locatieArray = $locatie_lijst;
        }

        if (array_key_exists('geslacht', $filter)) {
            $this->geslacht = $filter['geslacht'];
        }

        if (array_key_exists('amoc_landen', $filter)) {
            $this->landen = $filter['amoc_landen'];
        }

        if (array_key_exists('referentieperiode', $filter)) {
            $this->referentieperiode = $filter['referentieperiode'];
        }

        return parent::setFilter($filter);
    }

    protected function init()
    {
        if (!$this->landen || 0 === count($this->landen)) {
            return;
        }

        $refStartDate = (clone $this->startDate);
        $refEndDate = (clone $this->endDate);

        switch ($this->referentieperiode) {
            case 0: // last year
                $refStartDate->modify('-1 year')->modify('first day of january');
                $refEndDate->modify('-1 year')->modify('last day of december');
                break;
            case 1: // whole year before
                $refEndDate = (clone $this->startDate)->modify('-1 day');
                $refStartDate = (clone $refEndDate)->modify('-1 year + 1 day');
                break;
            case 2: // same period, one year earlier
            default:
                $refStartDate->modify('-1 year');
                $refEndDate->modify('-1 year');
                break;
        }

        $count[0] = $this->getData($this->startDate, $this->endDate);
        $count[1] = $this->getData($refStartDate, $refEndDate);

        $periode = sprintf('%s - %s', $this->startDate->format('d-m-Y'), $this->endDate->format('d-m-Y'));
        $referentie = sprintf('%s - %s', $refStartDate->format('d-m-Y'), $refEndDate->format('d-m-Y'));

        $this->reports[] = [
            'title' => 'Landen',
            'data' => ['Klanten uit' => ['' => implode($count[0]['amoc_landen'], ', ')]],
        ];

        $this->reports[] = [
            'title' => 'Basisstatistieken',
            'data' => [
                'Nieuwe klanten binnen periode' => [
                    $periode => $count[0]['totalNewClients'],
                    $referentie => $count[1]['totalNewClients'],
                ],
                'Unieke bezoekers' => [
                    $periode => $count[0]['uniqueVisits'],
                    $referentie => $count[1]['uniqueVisits'],
                ],
                'Aantal bezoeken inloophuis' => [
                    $periode => $count[0]['totalVisits'],
                    $referentie => $count[1]['totalVisits'],
                ],
                'Aantal verslagen Maatschappelijk Werk' => [
                    $periode => $count[0]['totalVerslagen'],
                    $referentie => $count[1]['totalVerslagen'],
                ],
                'Aantal doorverwijzingen' => [
                    $periode => $count[0]['doorverwijzingen_count'],
                    $referentie => $count[1]['doorverwijzingen_count'],
                ],
                'Gemiddelde leeftijd' => [
                    $periode => $count[0]['averageAge'],
                    $referentie => $count[1]['averageAge'],
                ],
            ],
        ];

        $perLand = [];
        foreach ($count[0]['clientsPerCountry'] as $i => $cnt) {
            $perLand[] = [
                'periode' => $periode,
                'land' => $count[0]['amoc_landen'][$i],
                'aantal' => $cnt,
            ];
        }
        foreach ($count[1]['clientsPerCountry'] as $i => $cnt) {
            $perLand[] = [
                'periode' => $referentie,
                'land' => $count[1]['amoc_landen'][$i],
                'aantal' => $cnt,
            ];
        }

        $table = new Table($perLand, 'periode', 'land', 'aantal');
        $table->setXTotals(false)->setYTotals(false)->setXSort(false);
        $this->reports[] = [
            'title' => 'Aantal ingeschreven personen per land',
            'yDescription' => 'Land',
            'data' => $table->render(),
        ];

        $perLeeftijd = [];
        foreach ($count[0]['ages'] as $i => $cnt) {
            $perLeeftijd[] = [
                'periode' => $periode,
                'leeftijd' => $i,
                'aantal' => $cnt,
            ];
        }
        foreach ($count[1]['ages'] as $i => $cnt) {
            $perLeeftijd[] = [
                'periode' => $referentie,
                'leeftijd' => $i,
                'aantal' => $cnt,
            ];
        }

        $table = new Table($perLeeftijd, 'periode', 'leeftijd', 'aantal');
        $table->setXTotals(false)->setYTotals(false)->setXSort(false);
        $this->reports[] = [
            'title' => 'Leeftijd',
            'yDescription' => 'Leeftijd',
            'data' => $table->render(),
        ];

        $perProblematiek = [];
        foreach ($count[0]['primaryProblems'] as $i => $cnt) {
            $perProblematiek[] = [
                'periode' => $periode,
                'problematiek' => $i ? $count[0]['primaireproblematiek'][$i] : '',
                'aantal' => $cnt,
            ];
        }
        foreach ($count[1]['primaryProblems'] as $i => $cnt) {
            $perProblematiek[] = [
                'periode' => $referentie,
                'problematiek' => $i ? $count[1]['primaireproblematiek'][$i] : '',
                'aantal' => $cnt,
            ];
        }

        $table = new Table($perProblematiek, 'periode', 'problematiek', 'aantal');
        $table->setXTotals(false)->setYTotals(false)->setXSort(false);
        $this->reports[] = [
            'title' => 'Primaire problematiek',
            'yDescription' => 'Problematiek',
            'data' => $table->render(),
        ];

        $perDoorverwijzing = [];
        foreach ($count[0]['count_per_doorverwijzing'] as $i => $cnt) {
            $perDoorverwijzing[] = [
                'periode' => $periode,
                'doorverwijzing' => $i ? $count[0]['doorverwijzingen'][$i] : '',
                'aantal' => $cnt,
            ];
        }
        foreach ($count[1]['count_per_doorverwijzing'] as $i => $cnt) {
            $perDoorverwijzing[] = [
                'periode' => $referentie,
                'doorverwijzing' => $i ? $count[1]['doorverwijzingen'][$i] : '',
                'aantal' => $cnt,
            ];
        }

        $table = new Table($perDoorverwijzing, 'periode', 'doorverwijzing', 'aantal');
        $table->setXTotals(false)->setYTotals(false)->setXSort(false);
        $this->reports[] = [
            'title' => 'Doorverwijzingen',
            'yDescription' => 'Doorverwijzing',
            'data' => $table->render(),
        ];
    }

    protected function build()
    {
        return;
    }

    /**
     * These logic and queries are copied from the original ECD-codebase.
     *
     * @todo Candidate for refactoring.
     *
     * @return array
     */
    private function getData(\DateTime $startDate, \DateTime $endDate)
    {
        $count = [
            'totalClients' => null,
            'totalNewClients' => null,
            'uniqueVisits' => null,
            'totalVisits' => null,
            'totalVerslagen' => null,
            'doorverwijzingen_count' => null,
            'averageAge' => null,
            'amoc_landen' => [],
            'clientsPerCountry' => [],
            'ages' => [],
            'primaryProblems' => [],
            'count_per_doorverwijzing' => [],
            'primaireproblematiek' => [],
            'doorverwijzingen' => [],
        ];

        $endDatePlusOneDay = (clone $endDate)->modify('+1 day');

        $klantRepository = $this->entityManager->getRepository(Klant::class);
        $registratieRepository = $this->entityManager->getRepository(Registratie::class);

        $builder = $klantRepository->createQueryBuilder('klant')
            ->select('klant.id, intake.id AS laatste_intake_id')
            ->join('klant.laatsteIntake', 'intake')
            ->andWhere('klant.created < :created')->setParameter('created', $endDatePlusOneDay)
        ;

        if ($this->geslacht instanceof Geslacht) {
            $builder->andWhere('klant.geslacht = :geslacht')->setParameter('geslacht', $this->geslacht);
        }

        if (count($this->landen) > 0) {
            $builder->andWhere('klant.land IN (:landen)')->setParameter('landen', $this->landen);
        }

//        echo $this->getFullSQL($builder->getQuery());
        $klanten = $builder->getQuery()->getResult();
        //@todo deze klantenlijst wordt al gefilterd op delete en disabled dus dat hoeft niet in de joins verderop.



        if ($this->locatie instanceof Locatie) {
            $builder = $registratieRepository->createQueryBuilder('registratie')
                ->select('klant.id')
                ->innerJoin('registratie.klant', 'klant')
                ->where('klant IN (:klanten)')->setParameter('klanten', $klanten)
                ->andWhere('registratie.locatie IN (:locatie)')->setParameter('locatie', $this->locatieArray)
                ->andWhere('registratie.binnen >= :start_date')->setParameter('start_date', $startDate)
                ->andWhere('registratie.binnen < :end_date')->setParameter('end_date', $endDatePlusOneDay)
            ;
            $klanten_list = array_map(function ($row) {
                return $row['id'];
            }, $builder->getQuery()->getScalarResult());

            $builder = $this->entityManager->getRepository(Verslag::class)->createQueryBuilder('verslag')
                ->select('klant.id')
                ->innerJoin('verslag.klant', 'klant')
                ->where('klant IN (:klanten)')->setParameter('klanten', $klanten)
                ->andWhere('verslag.locatie IN (:locatie)')->setParameter('locatie', $this->locatieArray)
                ->andWhere('verslag.datum >= :start_date')->setParameter('start_date', $startDate)
                ->andWhere('verslag.datum < :end_date')->setParameter('end_date', $endDatePlusOneDay)
            ;
            $klanten_list_verslag = array_map(function ($row) {
                return $row['id'];
            }, $builder->getQuery()->getScalarResult());

            $builder = $this->entityManager->getRepository(Intake::class)->createQueryBuilder('intake')
                ->select('klant.id')
                ->innerJoin('intake.klant', 'klant')
                ->where('klant IN (:klanten)')->setParameter('klanten', $klanten)
                ->andWhere('intake.intakelocatie IN (:locatie)')->setParameter('locatie', $this->locatieArray)
            ;
            $klanten_list_intake = array_map(function ($row) {
                return $row['id'];
            }, $builder->getQuery()->getScalarResult());

            $tmp_klanten = $klanten;
            $klanten = [];
            foreach ($tmp_klanten as $klant) {
                if (in_array($klant['id'], $klanten_list_intake)) {
                    $klanten[$klant['id']] = $klant['laatste_intake_id'];
                    continue;
                }
                if (in_array($klant['id'], $klanten_list)) {
                    $klanten[$klant['id']] = $klant['laatste_intake_id'];
                    continue;
                }
                if (in_array($klant['id'], $klanten_list_verslag)) {
                    $klanten[$klant['id']] = $klant['laatste_intake_id'];
                    continue;
                }
            }
        } else {
            $tmp_klanten = $klanten;
            $klanten = [];
            foreach ($tmp_klanten as $klant) {
                $klanten[$klant['id']] = $klant['laatste_intake_id'];
            }
            /**
             * $locatie = null dus alle locaties...
             *
             */
        }

        foreach ($this->landen as $land) {
            $count['amoc_landen'][$land->getId()] = $land->getNaam();
        }

        $verslavingen = $this->entityManager->getRepository(Verslaving::class)->findAll();
        foreach ($verslavingen as $verslaving) {
            $count['primaireproblematiek'][$verslaving->getId()] = $verslaving->getNaam();
        }

        $doorverwijzingen = $this->entityManager->getRepository(Doorverwijzing::class)->findAll();
        foreach ($doorverwijzingen as $doorverwijzing) {
            $count['doorverwijzingen'][$doorverwijzing->getId()] = $doorverwijzing->getNaam();
        }

        $count['totalClients'] = count($klanten);

        $count['totalNewClients']  = $klantRepository->createQueryBuilder('klant')
            ->select('COUNT(klant.id) as cnt')
            ->where('klant.id IN (:ids)')
            ->andWhere('klant.created >= :start_date')
            ->setParameter('ids', array_keys($klanten))
            ->setParameter('start_date', $startDate)
            ->getQuery()
            ->getSingleScalarResult();

        $count['uniqueVisits'] = $registratieRepository->createQueryBuilder('registratie')
            ->select('COUNT(DISTINCT klant.id) as cnt')
            ->innerJoin('registratie.klant', 'klant')
            ->where('klant.id IN (:ids)')
            ->andWhere('registratie.locatie IN (:locatie)')
            ->andWhere('registratie.binnen >= :start_date')
            ->andWhere('registratie.binnen < :end_date')
            ->setParameter('ids', array_keys($klanten))
            ->setParameter('locatie',$this->locatieArray)
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDatePlusOneDay)
            ->getQuery()
            ->getSingleScalarResult();

        $count['totalVisits'] = $registratieRepository->createQueryBuilder('registratie')
            ->select('COUNT(registratie.id) AS cnt')
            ->innerJoin('registratie.klant', 'klant')
            ->where('klant.id IN (:ids)')
            ->andWhere('registratie.locatie = :locatie')
            ->andWhere('registratie.binnen >= :start_date')
            ->andWhere('registratie.binnen < :end_date')
            ->setParameter('ids', array_keys($klanten))
            ->setParameter('locatie', $this->locatie)
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDatePlusOneDay)
            ->getQuery()
            ->getSingleScalarResult();

        $tbuilder = $registratieRepository->createQueryBuilder('registratie');
        $tbuilder
        ->select('COUNT(registratie.id) AS cnt')
        ->innerJoin('registratie.klant', 'klant')
        ->where('klant.id IN (:ids)')
        ->andWhere('registratie.locatie IN (:locatie)')
        ->andWhere('registratie.binnen >= :start_date')
        ->andWhere('registratie.binnen < :end_date')
        ->setParameter('ids', array_keys($klanten))
        ->setParameter('locatie', $this->locatieArray)
        ->setParameter('start_date', $startDate)
        ->setParameter('end_date', $endDatePlusOneDay)
        ;

        $builder = $this->entityManager->getRepository(Verslag::class)->createQueryBuilder('verslag')
            ->select('klant.id')
            ->innerJoin('verslag.klant', 'klant')
            ->where('klant.id IN (:ids)')
            ->andWhere('verslag.locatie IN (:locatie)')
            ->andWhere('verslag.datum >= :start_date')
            ->andWhere('verslag.datum < :end_date')
            ->setParameter('ids', array_keys($klanten))
            ->setParameter('locatie', $this->locatieArray)
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDatePlusOneDay)
        ;
        $verslagen = array_map(function ($row) {
            return $row['id'];
        }, $builder->getQuery()->getScalarResult());
        $count['totalVerslagen'] = count($verslagen);

        $count['count_per_doorverwijzing'] = [];
        $builder = $this->entityManager->getRepository(Verslaginventarisatie::class)->createQueryBuilder('verslaginventarisatie')
            ->select('doorverwijzing.id, COUNT(verslaginventarisatie.id) AS cnt')
            ->innerJoin('verslaginventarisatie.verslag', 'verslag')
            ->innerJoin('verslaginventarisatie.inventarisatie', 'inventarisatie')
            ->innerJoin('verslaginventarisatie.doorverwijzing', 'doorverwijzing')
            ->where('verslag.id IN (:ids)')
            ->groupBy('doorverwijzing.id')
            ->setParameter('ids', array_keys($verslagen))
        ;
        $result = $builder->getQuery()->getResult();
        foreach ($result as $row) {
            $count['count_per_doorverwijzing'][$row['id']] = $row['cnt'];
        }
        $count['doorverwijzingen_count'] = array_sum($count['count_per_doorverwijzing']);

        $count['clientsPerCountry'] = [];
        $result = $klantRepository->createQueryBuilder('klant')
            ->select('land.id, COUNT(klant.id) as cnt')
            ->innerJoin('klant.land', 'land')
            ->where('klant.id IN (:ids)')
            ->groupBy('land.id')
            ->orderBy('land.id')
            ->setParameters([
                'ids' => array_keys($klanten),
            ])
            ->getQuery()
            ->getResult();
        foreach ($result as $row) {
            $count['clientsPerCountry'][$row['id']] = $row['cnt'];
        }

        $today = new \DateTime('today');
        $count['ages'] = [];
        $result = $klantRepository->createQueryBuilder('klant')
            ->select('klant.geboortedatum, COUNT(klant.id) AS cnt')
            ->where('klant.id IN (:ids) AND klant.geboortedatum IS NOT NULL')
            ->groupBy('klant.geboortedatum')
            ->orderBy('klant.geboortedatum')
            ->setParameter('ids', array_keys($klanten))
            ->getQuery()
            ->getResult();
        foreach ($result as $values) {
            $age = $today->diff($values['geboortedatum'])->y;
            if (array_key_exists($age, $count['ages'])) {
                $count['ages'][$age] += $values['cnt'];
            } else {
                $count['ages'][$age] = $values['cnt'];
            }
        }
        ksort($count['ages']);

        $sum_ages = 0;
        $cnt_ages = 0;
        foreach ($count['ages'] as $age => $cnt) {
            if (empty($age)) {
                continue;
            } // exclude null values from the average
            $cnt_ages += $cnt;
            $sum_ages += $age * $cnt;
        }
        if ($cnt_ages > 0) {
            $count['averageAge'] = round($sum_ages / $cnt_ages, 1);
        } else {
            $count['averageAge'] = '--';
        }

        $result = $this->entityManager->getRepository(Intake::class)->createQueryBuilder('intake')
            ->select('problematiek.id, COUNT(klant.id) AS cnt')
            ->innerJoin('intake.primaireProblematiek', 'problematiek')
            ->innerJoin('intake.klant', 'klant')
            ->where('intake.id IN (:intakes)')
            ->groupBy('problematiek.id')
            ->orderBy('problematiek.id')
            ->setParameter('intakes', $klanten)
            ->getQuery()
            ->getResult()
        ;
        foreach ($result as $row) {
            $count['primaryProblems'][$row['id']] = $row['cnt'];
        }

        return $count;
    }
}
