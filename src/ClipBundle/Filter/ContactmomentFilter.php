<?php

namespace ClipBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Vraagsoort;
use AppBundle\Entity\Medewerker;
use AppBundle\Form\Model\AppDateRangeModel;
use ClipBundle\Entity\Behandelaar;

class ContactmomentFilter implements FilterInterface
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
     * @var Behandelaar
     */
    public $behandelaar;

    /**
     * @var VraagFilter
     */
    public $vraag;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('contactmoment.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if ($this->datum) {
            if ($this->datum->getStart()) {
                $builder
                    ->andWhere('contactmoment.datum >= :datum_van')
                    ->setParameter('datum_van', $this->datum->getStart())
                ;
            }
            if ($this->datum->getEnd()) {
                $builder
                    ->andWhere('contactmoment.datum <= :datum_tot')
                    ->setParameter('datum_tot', $this->datum->getEnd())
                ;
            }
        }

        if ($this->behandelaar) {
            $builder
                ->andWhere('contactmoment.behandelaar = :behandelaar')
                ->setParameter('behandelaar', $this->behandelaar)
            ;
        }

        if ($this->vraag) {
            $this->vraag->applyTo($builder);
        }
    }
}
