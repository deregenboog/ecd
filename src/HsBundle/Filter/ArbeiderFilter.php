<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class ArbeiderFilter implements FilterInterface
{
    public const STATUS_ACTIVE = 'Actief';
    public const STATUS_NON_ACTIVE = 'Niet actief';

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $hulpverlener;

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

        if ($this->hulpverlener) {
            $parts = preg_split('/\s+/', $this->hulpverlener);
            $fields = ["{$this->alias}.naamHulpverlener", "{$this->alias}.organisatieHulpverlener"];
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere("CONCAT_WS(' ', ".implode(', ', $fields).") LIKE :{$this->alias}_hulpverlener_part_{$i}")
                    ->setParameter("{$this->alias}_hulpverlener_part_{$i}", "%{$part}%")
                ;
            }
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
