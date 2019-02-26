<?php

namespace IzBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\VrijwilligerFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Project;

class IzVrijwilligerFilter implements FilterInterface
{
    const ACTIEF_NU = 'nu';
    const ACTIEF_OOIT = 'ooit';

    /**
     * @var AppDateRangeModel
     */
    public $afsluitDatum;

    /**
     * @var bool
     */
    public $openDossiers = true;

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
                        ->andWhere('hulpaanbod.koppelingEinddatum IS NULL')
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
                ->leftJoin('izVrijwilliger.hulpaanbiedingen', 'actiefHulpaanbod', 'WITH', $builder->expr()->andX(
                    'actiefHulpaanbod.hulpvraag IS NULL',
                    'actiefHulpaanbod.einddatum IS NULL OR actiefHulpaanbod.einddatum >= :now'
                ))
                ->addGroupBy('izVrijwilliger.id')
                ->andHaving('COUNT(actiefHulpaanbod) = 0')
                ->setParameter('now', new \DateTime())
            ;

            $builder
                ->andWhere('izVrijwilliger.id IN (:zonder_actief_hulpaanbod)')
                ->setParameter('zonder_actief_hulpaanbod', $this->getIds($subBuilder))
            ;
        }

        if ($this->zonderActieveKoppeling) {
            $subBuilder = $this->getSubBuilder($builder)
                ->select('izVrijwilliger.id')
                ->leftJoin('izVrijwilliger.hulpaanbiedingen', 'actieveKoppeling', 'WITH', $builder->expr()->andX(
                    'actieveKoppeling.hulpvraag IS NOT NULL',
                    'actieveKoppeling.koppelingEinddatum IS NULL OR actieveKoppeling.koppelingEinddatum >= :now'
                ))
                ->addGroupBy('izVrijwilliger.id')
                ->andHaving('COUNT(actieveKoppeling) = 0')
                ->setParameter('now', new \DateTime())
            ;

            $builder
                ->andWhere('izVrijwilliger.id IN (:zonder_actieve_koppeling)')
                ->setParameter('zonder_actieve_koppeling', $this->getIds($subBuilder))
            ;
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
