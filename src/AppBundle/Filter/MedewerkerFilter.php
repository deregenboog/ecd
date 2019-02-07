<?php

namespace AppBundle\Filter;

use Doctrine\ORM\QueryBuilder;

class MedewerkerFilter implements FilterInterface
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $naam;

    /**
     * @var string
     */
    public $rol;

    /**
     * @var bool
     */
    public $actief = true;

    public function applyTo(QueryBuilder $builder, $alias = 'medewerker')
    {
        if ($this->username) {
            $builder
                ->andWhere("{$alias}.username LIKE :{$alias}_username")
                ->setParameter("{$alias}_username", "%{$this->username}%")
            ;
        }

        if ($this->naam) {
            $parts = preg_split('/\s+/', $this->naam);
            $fields = ["{$alias}.voornaam", "{$alias}.tussenvoegsel", "{$alias}.achternaam"];
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere("CONCAT_WS(' ', ".implode(', ', $fields).") LIKE :{$alias}_naam_part_{$i}")
                    ->setParameter("{$alias}_naam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->rol) {
            $builder
                ->andWhere("{$alias}.roles LIKE :{$alias}_rol")
                ->setParameter("{$alias}_rol", "%{$this->rol}%")
            ;
        }

        if (!is_null($this->actief)) {
            if ($this->actief) {
                $builder->andWhere("{$alias}.actief = true");
            } else {
                $builder->andWhere("{$alias}.actief = false");
            }
        }
    }
}
