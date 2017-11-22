<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class ArbeiderFilter implements FilterInterface
{
    const STATUS_ACTIVE = 'Actief';
    const STATUS_NON_ACTIVE = 'Niet actief';

    /**
     * @var int
     */
    public $id;

    /**
     * @var bool
     */
    public $rijbewijs;

    /**
     * @var string
     */
    public $status = self::STATUS_ACTIVE;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere("{$this->alias}.id = :{$this->alias}_id")
                ->setParameter("{$this->alias}_id", $this->id)
            ;
        }

        if ($this->rijbewijs) {
            $builder->andWhere("{$this->alias}.rijbewijs = true");
        }

        if ($this->status) {
            switch ($this->status) {
                case self::STATUS_ACTIVE:
                    $builder->andWhere("{$this->alias}.actief = true");
                    break;
                case self::STATUS_NON_ACTIVE:
                    $builder->andWhere("{$this->alias}.actief = false");
                    break;
                default:
                    break;
            }
        }
    }
}
