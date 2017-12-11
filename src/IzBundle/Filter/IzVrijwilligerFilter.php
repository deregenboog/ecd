<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\VrijwilligerFilter;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;

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
     * @var IzProject
     */
    public $izProject;

    /**
     * @var Medewerker
     */
    public $izIntakeMedewerker;

    /**
     * @var Medewerker
     */
    public $izHulpaanbodMedewerker;

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

        if ($this->izProject) {
            switch ($this->actief) {
                case self::ACTIEF_OOIT:
                    $builder
                        ->andWhere('izHulpaanbod.izProject = :izProject')
                        ->setParameter('izProject', $this->izProject)
                    ;
                    break;
                case self::ACTIEF_NU:
                default:
                    $builder
                        ->andWhere('izHulpaanbod.izProject = :izProject')
                        ->andWhere('izHulpaanbod.einddatum IS NULL')
                        ->andWhere('izHulpaanbod.koppelingEinddatum IS NULL')
                        ->setParameter('izProject', $this->izProject)
                    ;
                    break;
            }
        }

        if ($this->izIntakeMedewerker) {
            $builder
                ->andWhere('izIntakeMedewerker = :izIntakeMedewerker')
                ->setParameter('izIntakeMedewerker', $this->izIntakeMedewerker)
            ;
        }

        if ($this->izHulpaanbodMedewerker) {
            $builder
                ->andWhere('izHulpaanbodMedewerker = :izHulpaanbodMedewerker')
                ->setParameter('izHulpaanbodMedewerker', $this->izHulpaanbodMedewerker)
            ;
        }

        if ($this->zonderActiefHulpaanbod) {
            $subBuilder = $this->getSubBuilder($builder)
                ->select('izVrijwilliger.id')
                ->leftJoin('izVrijwilliger.izHulpaanbiedingen', 'actiefIzHulpaanbod', 'WITH', $builder->expr()->andX(
                    'actiefIzHulpaanbod.izHulpvraag IS NULL',
                    'actiefIzHulpaanbod.einddatum IS NULL OR actiefIzHulpaanbod.einddatum >= :now'
                ))
                ->addGroupBy('izVrijwilliger.id')
                ->andHaving('COUNT(actiefIzHulpaanbod) = 0')
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
                ->leftJoin('izVrijwilliger.izHulpaanbiedingen', 'actieveIzKoppeling', 'WITH', $builder->expr()->andX(
                    'actieveIzKoppeling.izHulpvraag IS NOT NULL',
                    'actieveIzKoppeling.koppelingEinddatum IS NULL OR actieveIzKoppeling.koppelingEinddatum >= :now'
                ))
                ->addGroupBy('izVrijwilliger.id')
                ->andHaving('COUNT(actieveIzKoppeling) = 0')
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
        $subBuilder = clone($builder);

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
            function(array $item) {
                return $item['id'];
            },
            $builder->getQuery()->getResult()
        );
    }
}
