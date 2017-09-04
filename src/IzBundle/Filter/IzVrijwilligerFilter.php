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
            $builder
                ->andWhere('izHulpaanbod.izProject = :izProject')
                ->setParameter('izProject', $this->izProject)
            ;
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
            $builder
                ->leftJoin('izVrijwilliger.izHulpaanbiedingen', 'actiefIzHulpaanbod', 'WITH', $builder->expr()->andX(
                    'actiefIzHulpaanbod.izHulpvraag IS NULL',
                    'actiefIzHulpaanbod.einddatum IS NULL OR actiefIzHulpaanbod.einddatum >= :now'
                ))
                ->addGroupBy('izVrijwilliger.id')
                ->andHaving('COUNT(actiefIzHulpaanbod) = 0')
                ->setParameter('now', new \DateTime())
            ;
        }

        if ($this->zonderActieveKoppeling) {
            $builder
                ->leftJoin('izVrijwilliger.izHulpaanbiedingen', 'actieveIzKoppeling', 'WITH', $builder->expr()->andX(
                    'actieveIzKoppeling.izHulpvraag IS NOT NULL',
                    'actieveIzKoppeling.koppelingEinddatum IS NULL OR actieveIzKoppeling.koppelingEinddatum >= :now'
                ))
                ->addGroupBy('izVrijwilliger.id')
                ->andHaving('COUNT(actieveIzKoppeling) = 0')
                ->setParameter('now', new \DateTime())
            ;
        }
    }
}
