<?php

namespace AppBundle\Filter;

use AppBundle\Entity\Klant;
use Doctrine\ORM\QueryBuilder;

class KlantFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $naam;

    /**
     * @var \DateTime
     */
    public $geboortedatum;

    /**
     * @var string
     */
    public $stadsdeel;

    public function applyTo(QueryBuilder $builder, $alias = 'klant')
    {
        if ($this->id) {
            $builder
                ->andWhere("{$alias}.id = :{$alias}_id")
                ->setParameter("{$alias}_id", $this->id)
            ;
        }

        if ($this->naam) {
            $parts = preg_split('/\s+/', $this->naam);
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere("CONCAT_WS(' ', {$alias}.voornaam, {$alias}.roepnaam, {$alias}.tussenvoegsel, {$alias}.achternaam) LIKE :{$alias}_naam_part_{$i}")
                    ->setParameter("{$alias}_naam_part_{$i}", "%{$part}%")
            ;
            }
        }

        if ($this->geboortedatum) {
            $builder
                ->andWhere("{$alias}.geboortedatum = :{$alias}_geboortedatum")
                ->setParameter("{$alias}_geboortedatum", $this->geboortedatum)
            ;
        }

        if (isset($this->stadsdeel)) {
            $builder
                ->andWhere("{$alias}.werkgebied = :{$alias}_stadsdeel")
                ->setParameter("{$alias}_stadsdeel", $this->stadsdeel)
            ;
        }
    }
}
