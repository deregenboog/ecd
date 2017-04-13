<?php

namespace AppBundle\Filter;

use AppBundle\Entity\Klant;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Form\Model\AppDateRangeModel;

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
    public $bsn;

    /**
     * @var \DateTime
     */
    public $geboortedatum;

    /**
     * @var AppDateRangeModel
     */
    public $geboortedatumRange;

    /**
     * @var string
     */
    public $stadsdeel;

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

        if ($this->bsn) {
            $builder
                ->andWhere("{$this->alias}.bsn= :{$this->alias}_bsn")
                ->setParameter("{$this->alias}_bsn", $this->bsn)
            ;
        }

        if ($this->geboortedatum) {
            $builder
                ->andWhere("{$this->alias}.geboortedatum = :{$this->alias}_geboortedatum")
                ->setParameter("{$this->alias}_geboortedatum", $this->geboortedatum)
            ;
        }

        if ($this->geboortedatumRange) {
            if ($this->geboortedatumRange->getStart()) {
                $builder
                    ->andWhere("{$this->alias}.geboortedatum >= :{$this->alias}_geboortedatum_van")
                    ->setParameter("{$this->alias}_geboortedatum_van", $this->geboortedatum->getStart())
                ;
            }
            if ($this->geboortedatumRange->getEnd()) {
                $builder
                    ->andWhere("{$this->alias}.geboortedatum <= :{$this->alias}_geboortedatum_tot")
                    ->setParameter("{$this->alias}_geboortedatum_tot", $this->geboortedatum->getEnd())
                ;
            }
        }

        if (isset($this->stadsdeel)) {
            if ($this->stadsdeel == '-') {
                $builder->andWhere("{$this->alias}.werkgebied IS NULL OR {$this->alias}.werkgebied = ''");
            } else {
            $builder
                ->andWhere("{$this->alias}.werkgebied = :{$this->alias}_stadsdeel")
                ->setParameter("{$this->alias}_stadsdeel", $this->stadsdeel)
            ;
            }
        }
    }
}
