<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\OekGroep;
use AppBundle\Form\Model\AppDateRangeModel;
use OekBundle\Entity\OekAanmelding;

class OekKlantFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var OekGroep
     */
    public $groep;

    /**
     * @var OekTraining
     */
    public $training;

    /**
     * @var AppDateRangeModel
     */
    public $aanmelddatum;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('oekKlant.id = :oek_klant_id')
                ->setParameter('oek_klant_id', $this->id)
            ;
        }

        if ($this->groep) {
            $builder
                ->andWhere('oekGroep = :oek_groep')
                ->setParameter('oek_groep', $this->groep)
            ;
        }

        if ($this->training) {
            $builder
                ->andWhere('oekTraining = :oek_training')
                ->setParameter('oek_training', $this->training)
            ;
        }

        if ($this->aanmelddatum) {
            if ($this->aanmelddatum->getStart()) {
                $builder
                    ->andWhere('oekAanmelding.datum >= :aanmelddatum_van')
                    ->setParameter('aanmelddatum_van', $this->aanmelddatum->getStart())
                ;
            }
            if ($this->aanmelddatum->getEnd()) {
                $builder
                    ->andWhere('oekAanmelding.datum <= :aanmelddatum_tot')
                    ->setParameter('aanmelddatum_tot', $this->aanmelddatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('oekAfsluiting.datum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('oekAfsluiting.datum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
