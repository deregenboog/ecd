<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Entity\Activiteit;
use AppBundle\Form\Model\AppDateRangeModel;

class KlusFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var AppDateRangeModel
     */
    public $datum;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var Activiteit
     */
    public $activiteit;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('klus.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if ($this->datum) {
            if ($this->datum->getStart()) {
                $builder
                    ->andWhere('klus.datum >= :datum_van')
                    ->setParameter('datum_van', $this->datum->getStart())
                ;
            }
            if ($this->datum->getEnd()) {
                $builder
                    ->andWhere('klus.datum <= :datum_tot')
                    ->setParameter('datum_tot', $this->datum->getEnd())
                ;
            }
        }

        if ($this->activiteit) {
            $builder
                ->andWhere('klus.activiteit = :activiteit')
                ->setParameter('activiteit', $this->activiteit)
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
