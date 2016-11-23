<?php

namespace AppBundle\Filter;

use AppBundle\Entity\Vrijwilliger;
use Doctrine\ORM\QueryBuilder;

class VrijwilligerFilter implements FilterInterface
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
            ->andWhere('vrijwilliger.id = :vrijwilliger_id')
            ->setParameter('vrijwilliger_id', $this->id)
            ;
        }

        if ($this->naam) {
            $builder
                ->andWhere('CONCAT(vrijwilliger.voornaam, vrijwilliger.roepnaam, vrijwilliger.tussenvoegsel, vrijwilliger.achternaam) LIKE :vrijwilliger_naam')
                ->setParameter('vrijwilliger_naam', "%{$this->naam}%")
            ;
        }

        if ($this->geboortedatum) {
            $builder
                ->andWhere('vrijwilliger.geboortedatum = :vrijwilliger_geboortedatum')
                ->setParameter('vrijwilliger_geboortedatum', $this->geboortedatum)
            ;
        }

        if (isset($this->stadsdeel['naam'])) {
            $builder
                ->andWhere('vrijwilliger.werkgebied = :vrijwilliger_stadsdeel')
                ->setParameter('vrijwilliger_stadsdeel', $this->stadsdeel['naam'])
            ;
        }
    }
}
