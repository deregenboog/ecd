<?php

namespace AppBundle\Filter;

use AppBundle\Entity\GgwGebied;
use AppBundle\Entity\Werkgebied;
use Doctrine\ORM\QueryBuilder;

class PostcodeFilter implements FilterInterface
{
    /**
     * @var string
     */
    public $postcode;

    /**
     * @var Werkgebied
     */
    public $stadsdeel;

    /**
     * @var GgwGebied
     */
    public $postcodegebied;

    public function applyTo(QueryBuilder $builder, $alias = 'postcode')
    {
        if ($this->postcode) {
            $builder
                ->andWhere("{$alias}.postcode LIKE :{$alias}_postcode")
                ->setParameter("{$alias}_postcode", "%{$this->postcode}%")
            ;
        }

        if ($this->stadsdeel) {
            $builder
                ->andWhere("{$alias}.stadsdeel = :{$alias}_stadsdeel")
                ->setParameter("{$alias}_stadsdeel", $this->stadsdeel)
            ;
        }

        if ($this->postcodegebied) {
            $builder
                ->andWhere("{$alias}.postcodegebied = :{$alias}_postcodegebied")
                ->setParameter("{$alias}_postcodegebied", $this->postcodegebied)
            ;
        }
    }
}
