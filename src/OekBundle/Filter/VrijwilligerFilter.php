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

        if($this->actief == true)
        {
            $builder->andWhere("vrijwilliger.actief = 1")
               ;
        }
        else
        {
            $builder->andWhere("vrijwilliger.actief = 0 OR vrijwilliger.actief = 1")
                ;
        }
        if($this->vrijwilliger)
        {
            $this->vrijwilliger->applyTo($builder, 'appVrijwilliger');
        }


    }
}
