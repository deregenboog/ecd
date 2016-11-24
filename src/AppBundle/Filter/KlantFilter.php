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

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('klant.id = :klant_id')
                ->setParameter('klant_id', $this->id)
            ;
        }

        if ($this->naam) {
            $parts = preg_split('/\s+/', $this->naam);
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere("CONCAT_WS(' ', klant.voornaam, klant.roepnaam, klant.tussenvoegsel, klant.achternaam) LIKE :klant_naam_part_{$i}")
                    ->setParameter("klant_naam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->geboortedatum) {
            $builder
                ->andWhere('klant.geboortedatum = :klant_geboortedatum')
                ->setParameter('klant_geboortedatum', $this->geboortedatum)
            ;
        }

        if (isset($this->stadsdeel['naam'])) {
            $builder
                ->andWhere('klant.werkgebied = :klant_stadsdeel')
                ->setParameter('klant_stadsdeel', $this->stadsdeel['naam'])
            ;
        }
    }
}
