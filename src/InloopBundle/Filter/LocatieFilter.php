<?php

namespace InloopBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class LocatieFilter implements FilterInterface
{
    public const STATUS_INACTIEF = 0;
    public const STATUS_ACTIEF = 1;
    /**
     * @var bool
     */
    public $status = self::STATUS_ACTIEF;

    /** @var string */
    public $naam;

    /**
     * @var LocatieType
     */
    public $locatieTypes;

    public function applyTo(QueryBuilder $builder) {

        if($this->naam) {
            $builder
                ->andWhere('locatie.naam LIKE :naam')
                ->setParameter('naam',"%".$this->naam."%");

        }

        if($this->locatieTypes)
        {
            $builder
                ->innerJoin('locatie.locatieTypes','locatieTypes')

                ->andWhere('locatieTypes IN (:locatietypes)')
                ->setParameter('locatietypes',$this->locatieTypes);

        }

        if (null !== $this->status) {
            if ($this->status == self::STATUS_ACTIEF) {
                $builder
                    ->andWhere('locatie.datumVan <= :today')
                    ->andWhere('locatie.datumTot >= :today OR locatie.datumTot IS NULL')
                    ->setParameter('today', new \DateTime('today'))
                ;
            }
            if ($this->status == self::STATUS_INACTIEF) {
                $builder
                    ->andWhere('locatie.datumVan >= :today')
                    ->andWhere('locatie.datumTot <= :today OR locatie.datumTot IS NULL')
                    ->setParameter('today', new \DateTime('today'))
                ;
            }
        }
    }
}
