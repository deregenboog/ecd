<?php

namespace GaBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\Activiteit;
use GaBundle\Entity\Groep;

abstract class DossierFilter implements FilterInterface
{
    /**
     * @var Groep
     */
    public $groep;

    /**
     * @var Activiteit
     */
    public $activiteit;

    /**
     * @var AppDateRangeModel
     */
    public $aanmelddatum;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->groep) {
            $builder
                ->andWhere('groep = :groep')
                ->setParameter('groep', $this->groep)
            ;
        }

        if ($this->activiteit) {
            $builder
                ->andWhere('activiteit= :activiteit')
                ->setParameter('activiteit', $this->activiteit)
            ;
        }

        if ($this->aanmelddatum) {
            if ($this->aanmelddatum->getStart()) {
                $builder
                    ->andWhere('dossier.aanmelddatum >= :aanmelddatum_van')
                    ->setParameter('aanmelddatum_van', $this->aanmelddatum->getStart())
                ;
            }
            if ($this->aanmelddatum->getEnd()) {
                $builder
                    ->andWhere('dossier.aanmelddatum <= :aanmelddatum_tot')
                    ->setParameter('aanmelddatum_tot', $this->aanmelddatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('dossier.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('dossier.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }
    }
}
