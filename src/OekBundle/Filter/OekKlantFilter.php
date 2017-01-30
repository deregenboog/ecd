<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\QueryBuilder;

class OekKlantFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

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

        if ($this->aanmelding) {
            $builder
                ->andWhere('oekKlant.aanmelding LIKE :aanmelding')
                ->setParameter('aanmelding', "%{$this->aanmelding}%")
            ;
        }

        if ($this->afsluiting) {
            $builder
                ->andWhere('oekKlant.afsluiting LIKE :afsluiting')
                ->setParameter('afsluiting', "%{$this->afsluiting}%")
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
