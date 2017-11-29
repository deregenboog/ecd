<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;

class IzKlantFilter implements FilterInterface
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
     * @var KlantFilter
     */
    public $klant;

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
    public $izHulpvraagMedewerker;

    /**
     * @var bool
     */
    public $zonderActieveHulpvraag;

    /**
     * @var bool
     */
    public $zonderActieveKoppeling;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->afsluitDatum) {
            if ($this->afsluitDatum->getStart()) {
                $builder
                    ->andWhere('izKlant.afsluitDatum >= :izKlant_afsluitDatum_van')
                    ->setParameter('izKlant_afsluitDatum_van', $this->afsluitDatum->getStart())
                ;
            }
            if ($this->afsluitDatum->getEnd()) {
                $builder
                    ->andWhere('izKlant.afsluitDatum <= :izKlant_afsluitDatum_tot')
                    ->setParameter('izKlant_afsluitDatum_tot', $this->afsluitDatum->getEnd())
                ;
            }
        }

        if ($this->openDossiers) {
            $builder
                ->andWhere('izKlant.afsluitDatum IS NULL OR izKlant.afsluitDatum > :now')
                ->setParameter('now', new \DateTime())
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->izProject) {
            switch ($this->actief) {
                case self::ACTIEF_OOIT:
                    $builder
                        ->andWhere('izHulpvraag.izProject = :izProject')
                        ->setParameter('izProject', $this->izProject)
                    ;
                    break;
                case self::ACTIEF_NU:
                default:
                    $builder
                        ->andWhere('izHulpvraag.izProject = :izProject')
                        ->andWhere('izHulpvraag.einddatum IS NULL')
                        ->andWhere('izHulpvraag.koppelingEinddatum IS NULL')
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

        if ($this->izHulpvraagMedewerker) {
            $builder
                ->andWhere('izHulpvraagMedewerker = :izHulpvraagMedewerker')
                ->setParameter('izHulpvraagMedewerker', $this->izHulpvraagMedewerker)
            ;
        }

        if ($this->zonderActieveHulpvraag) {
            $subBuilder = $this->getSubBuilder($builder)
                ->select('izKlant.id')
                ->leftJoin('izKlant.izHulpvragen', 'actieveIzHulpvraag', 'WITH', $builder->expr()->andX(
                    'actieveIzHulpvraag.izHulpaanbod IS NULL',
                    'actieveIzHulpvraag.einddatum IS NULL OR actieveIzHulpvraag.einddatum >= :now'
                ))
                ->addGroupBy('izKlant.id')
                ->andHaving('COUNT(actieveIzHulpvraag) = 0')
                ->setParameter('now', new \DateTime())
            ;

            $builder
                ->andWhere('izKlant.id IN (:zonder_actieve_hulpvraag)')
                ->setParameter('zonder_actieve_hulpvraag', $this->getIds($subBuilder))
            ;
        }

        if ($this->zonderActieveKoppeling) {
            $subBuilder = $this->getSubBuilder($builder)
                ->select('izKlant.id')
                ->leftJoin('izKlant.izHulpvragen', 'actieveIzKoppeling', 'WITH', $builder->expr()->andX(
                    'actieveIzKoppeling.izHulpaanbod IS NOT NULL',
                    'actieveIzKoppeling.koppelingEinddatum IS NULL OR actieveIzKoppeling.koppelingEinddatum >= :now'
                ))
                ->addGroupBy('izKlant.id')
                ->andHaving('COUNT(actieveIzKoppeling) = 0')
                ->setParameter('now', new \DateTime())
            ;

            $builder
                ->andWhere('izKlant.id IN (:zonder_actieve_koppeling)')
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
