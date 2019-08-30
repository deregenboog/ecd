<?php

namespace OekBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\Vrijwilliger;

class VrijwilligerFilter implements FilterInterface
{
    public $alias = 'vrijwilliger';

    /**
     * @var bool
     */
    public $actief = true;


    /**
     * @var \AppBundle\Filter\VrijwilligerFilter
     */
    public $vrijwilliger;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->vrijwilliger) {
            if($this->actief)
            {
                $builder->andWhere("vrijwilliger.actief = :actief")
                    ->setParameter(":actief",$this->actief);
            }
            else
            {
                $builder->andWhere("vrijwilliger.actief = false")
                    ;
            }
            $this->vrijwilliger->applyTo($builder, 'appVrijwilliger');
        }
    }
}
