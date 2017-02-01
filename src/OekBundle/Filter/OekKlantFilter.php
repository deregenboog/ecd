<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\OekGroep;

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
     * @var string
     */
    public $aanmelding;

    /**
     * @var string
     */
    public $afsluiting;

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

        if ($this->aanmelding) {
            if ($this->aanmelding->getStart()) {
                $builder
                ->andWhere('oekKlant.aanmelding >= :aanmelding_van')
                ->setParameter('aanmelding_van', $this->aanmelding->getStart())
                ;
            }
            if ($this->aanmelding->getEnd()) {
                $builder
                    ->andWhere('oekKlant.aanmelding <= :aanmelding_tot')
                    ->setParameter('aanmelding_tot', $this->aanmelding->getEnd())
                ;
            }
        }

        if ($this->afsluiting) {
            if ($this->afsluiting->getStart()) {
                $builder
                    ->andWhere('oekKlant.afsluiting >= :afsluiting_van')
                    ->setParameter('afsluiting_van', $this->afsluiting->getStart())
                ;
            }
            if ($this->afsluiting->getEnd()) {
                $builder
                    ->andWhere('oekKlant.afsluiting <= :afsluiting_tot')
                    ->setParameter('afsluiting_tot', $this->afsluiting->getEnd())
                ;
            }
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
