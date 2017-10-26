<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class KlantFilter implements FilterInterface
{
    public $alias = 'klant';

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $naam;

    /**
     * @var string
     */
    public $stadsdeel;

    /**
     * @var bool
     */
    public $actief;

    /**
     * @var bool
     */
    public $negatiefSaldo;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere("{$this->alias}.id = :{$this->alias}_id")
                ->setParameter("{$this->alias}_id", $this->id)
            ;
        }

        if ($this->naam) {
            $parts = preg_split('/\s+/', $this->naam);
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere("CONCAT_WS(' ', {$this->alias}.voornaam, {$this->alias}.roepnaam, {$this->alias}.tussenvoegsel, {$this->alias}.achternaam) LIKE :{$this->alias}_naam_part_{$i}")
                    ->setParameter("{$this->alias}_naam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if (isset($this->stadsdeel)) {
            if ('-' == $this->stadsdeel) {
                $builder->andWhere("{$this->alias}.werkgebied IS NULL OR {$this->alias}.werkgebied = ''");
            } else {
                $builder
                    ->andWhere("{$this->alias}.werkgebied = :{$this->alias}_stadsdeel")
                    ->setParameter("{$this->alias}_stadsdeel", $this->stadsdeel)
                ;
            }
        }

        if ($this->actief) {
            $builder->andWhere("{$this->alias}.actief = true");
        }

        if ($this->negatiefSaldo) {
            $builder->andWhere("{$this->alias}.saldo < 0");
        }
    }
}
