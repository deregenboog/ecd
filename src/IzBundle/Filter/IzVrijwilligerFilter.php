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
     * @var boolean
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
    public $medewerker;

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

        if ($this->medewerker) {
            $builder
                ->andWhere('izHulpaanbod.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }
    }
}
