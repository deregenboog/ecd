<?php

namespace IzBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\VrijwilligerFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Deelnemerstatus;
use IzBundle\Entity\Project;

class IzVrijwilligerFilter implements FilterInterface
{
    const ACTIEF_NU = 'nu';
    const ACTIEF_OOIT = 'ooit';

    /**
     * @var string
     */
    public $status;

    /**
     * @var AppDateRangeModel
     */
    public $datumAanmelding;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitDatum;

    /**
     * @var bool
     */
    public $openDossiers;

    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

    /**
     * @var string
     */
    public $actief;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var Medewerker
     */
    public $intakeMedewerker;

    /**
     * @var Medewerker
     */
    public $hulpaanbodMedewerker;

    /**
     * @var bool
     */
    public $zonderActiefHulpaanbod;

    /**
     * @var bool
     */
    public $zonderActieveKoppeling;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->status) {
            if ('Kan gekoppeld worden' === $this->status) {
                // met open hulpaanbod
                $builder->andWhere('koppeling.id IS NULL');
            } else {
                $builder
                    ->andWhere('deelnemerstatus.naam = :statusnaam OR koppelingstatus.naam = :statusnaam')
                    ->setParameter('statusnaam', $this->status)
                ;
            }
        }

        if ($this->datumAanmelding) {
            if ($this->datumAanmelding->getStart()) {
                $builder
                    ->andWhere('izKlant.datumAanmelding >= :izKlant_datumAanmelding_van')
                    ->setParameter('izKlant_datumAanmelding_van', $this->datumAanmelding->getStart())
                ;
            }
            if ($this->datumAanmelding->getEnd()) {
                $builder
                    ->andWhere('izKlant.datumAanmelding <= :izKlant_datumAanmelding_tot')
                    ->setParameter('izKlant_datumAanmelding_tot', $this->datumAanmelding->getEnd())
                ;
            }
        }

        if ($this->afsluitDatum) {
            if ($this->afsluitDatum->getStart()) {
                $builder
                    ->andWhere('izVrijwilliger.afsluitDatum >= :izVrijwilliger_afsluitDatum_van')
                    ->setParameter('izVrijwilliger_afsluitDatum_van', $this->afsluitDatum->getStart())
                ;
            }
            if ($this->afsluitDatum->getEnd()) {
                $builder
                    ->andWhere('izVrijwilliger.afsluitDatum <= :izVrijwilliger_afsluitDatum_tot')
                    ->setParameter('izVrijwilliger_afsluitDatum_tot', $this->afsluitDatum->getEnd())
                ;
            }
        }

        if ($this->openDossiers) {
            $builder
                ->andWhere('izVrijwilliger.afsluitDatum IS NULL OR izVrijwilliger.afsluitDatum > :now')
                ->setParameter('now', new \DateTime())
            ;
        }

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }

        if ($this->project) {
            switch ($this->actief) {
                case self::ACTIEF_OOIT:
                    $builder
                        ->andWhere('hulpaanbod.project = :project')
                        ->setParameter('project', $this->project)
                    ;
                    break;
                case self::ACTIEF_NU:
                default:
                    $builder
                        ->andWhere('hulpaanbod.project = :project')
                        ->andWhere('hulpaanbod.einddatum IS NULL')
                        ->andWhere('koppeling.afsluitdatum IS NULL')
                        ->setParameter('project', $this->project)
                    ;
                    break;
            }
        }

        if ($this->intakeMedewerker) {
            $builder
                ->andWhere('intakeMedewerker = :intakeMedewerker')
                ->setParameter('intakeMedewerker', $this->intakeMedewerker)
            ;
        }

        if ($this->hulpaanbodMedewerker) {
            $builder
                ->andWhere('hulpaanbodMedewerker = :hulpaanbodMedewerker')
                ->setParameter('hulpaanbodMedewerker', $this->hulpaanbodMedewerker)
            ;
        }

        if ($this->zonderActiefHulpaanbod) {
            $subBuilder = $this->getSubBuilder($builder)
                ->select('izVrijwilliger.id')
                ->innerJoin('izVrijwilliger.hulpaanbiedingen', 'hulpaanbod')
                ->leftJoin('hulpaanbod.koppeling', 'koppeling')
                ->andWhere($builder->expr()->andX(
                    'koppeling.id IS NULL',
                    'hulpaanbod.einddatum IS NULL OR hulpaanbod.einddatum >= :now'
                ))
                ->addGroupBy('izVrijwilliger.id')
                ->setParameter('now', new \DateTime())
            ;

            $metActiefHulpaanbod = $this->getIds($subBuilder);
            if ($metActiefHulpaanbod) {
                $builder
                    ->andWhere('izVrijwilliger.id NOT IN (:met_actief_hulpaanbod)')
                    ->setParameter('met_actief_hulpaanbod', $metActiefHulpaanbod)
                ;
            }
        }

        if ($this->zonderActieveKoppeling) {
            $subBuilder = $this->getSubBuilder($builder)
                ->select('izVrijwilliger.id')
                ->innerJoin('izVrijwilliger.hulpaanbiedingen', 'hulpaanbod')
                ->innerJoin('hulpaanbod.koppeling', 'koppeling')
                ->andWhere($builder->expr()->andX(
                    'koppeling.id IS NOT NULL',
                    'koppeling.afsluitdatum IS NULL OR koppeling.afsluitdatum >= :now'
                ))
                ->addGroupBy('izVrijwilliger.id')
                ->setParameter('now', new \DateTime())
            ;

            $metActieveKoppeling = $this->getIds($subBuilder);
            if ($metActieveKoppeling) {
                $builder
                    ->andWhere('izVrijwilliger.id IN (:met_actieve_koppeling)')
                    ->setParameter('met_actieve_koppeling', $this->getIds($subBuilder))
                ;
            }
        }
    }

    private function getSubBuilder(QueryBuilder $builder)
    {
        $subBuilder = clone $builder;

        // keep select and from parts, reset the rest
        $dqlParts = $subBuilder->getDQLParts();
        unset($dqlParts['select']);
        unset($dqlParts['from']);
        $subBuilder->resetDQLParts(array_keys($dqlParts))->setParameters([]);

        return $subBuilder;
    }

    private function getIds(QueryBuilder $builder)
    {
        return array_map(
            function (array $item) {
                return $item['id'];
            },
            $builder->getQuery()->getResult()
        );
    }
}
