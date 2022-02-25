<?php

namespace AppBundle\Filter;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\GgwGebied;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Werkgebied;
use AppBundle\Form\Model\AppDateRangeModel;
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
     * @var string
     */
    public $voornaam;

    /**
     * @var string
     */
    public $achternaam;

    /**
     * @var string
     */
    public $adres;

    /**
     * @var Geslacht
     */
    public $geslacht;

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
     * @var Werkgebied
     */
    public $stadsdeel;

    /**
     * @var GgwGebied
     */
    public $postcodegebied;

    /**
     * @var string
     */
    public $plaats;

    /**
     * @var Medewerker
     */
    public $medewerker;

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
                    ->andWhere($builder->expr()->orX(
                        "{$alias}.voornaam LIKE :{$alias}_naam_part_{$i}",
                        "{$alias}.roepnaam LIKE :{$alias}_naam_part_{$i}",
                        "{$alias}.tussenvoegsel LIKE :{$alias}_naam_part_{$i}",
                        "{$alias}.achternaam LIKE :{$alias}_naam_part_{$i}"
                    ))
                    ->setParameter("{$alias}_naam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->voornaam) {
            $parts = preg_split('/\s+/', $this->voornaam);
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere($builder->expr()->orX(
                        "{$alias}.voornaam LIKE :{$alias}_voornaam_part_{$i}",
                        "{$alias}.roepnaam LIKE :{$alias}_voornaam_part_{$i}"
                    ))
                    ->setParameter("{$alias}_voornaam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->achternaam) {
            $parts = preg_split('/\s+/', $this->achternaam);
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere($builder->expr()->orX(
                        "{$alias}.tussenvoegsel LIKE :{$alias}_achternaam_part_{$i}",
                        "{$alias}.achternaam LIKE :{$alias}_achternaam_part_{$i}"
                    ))
                    ->setParameter("{$alias}_achternaam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->adres) {
            $parts = preg_split('/\s+/', $this->adres);
            $fields = ["{$alias}.adres", "{$alias}.postcode", "{$alias}.plaats", "{$alias}.telefoon", "{$alias}.mobiel"];
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere("CONCAT_WS(' ', ".implode(', ', $fields).") LIKE :{$alias}_adres_part_{$i}")
                    ->setParameter("{$alias}_adres_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->geslacht && (is_array($this->geslacht) || $this->geslacht instanceof \Countable ? count($this->geslacht) : 0)>0) {
            $builder
                ->andWhere("{$alias}.geslacht IN (:{$alias}_geslacht)")
                ->setParameter("{$alias}_geslacht", $this->geslacht)
            ;
        }

        if ($this->bsn) {
            $tmpBsn = substr($this->bsn,1,strlen($this->bsn)-2);
            $builder
                ->andWhere("{$alias}.bsn LIKE :{$alias}_bsn")
                ->setParameter("{$alias}_bsn", "%{$tmpBsn}%")
            ;
        }

        if ($this->geboortedatum) {
            $builder
                ->andWhere("{$alias}.geboortedatum = :{$alias}_geboortedatum")
                ->setParameter("{$alias}_geboortedatum", $this->geboortedatum)
            ;
        }

        if ($this->geboortedatumRange) {
            if ($this->geboortedatumRange->getStart()) {
                $builder
                    ->andWhere("{$alias}.geboortedatum >= :{$alias}_geboortedatum_van")
                    ->setParameter("{$alias}_geboortedatum_van", $this->geboortedatumRange->getStart())
                ;
            }
            if ($this->geboortedatumRange->getEnd()) {
                $builder
                    ->andWhere("{$alias}.geboortedatum <= :{$alias}_geboortedatum_tot")
                    ->setParameter("{$alias}_geboortedatum_tot", $this->geboortedatumRange->getEnd())
                ;
            }
        }

        if ($this->stadsdeel) {
            $builder
                ->andWhere("{$alias}.werkgebied = :{$alias}_stadsdeel")
                ->setParameter("{$alias}_stadsdeel", $this->stadsdeel)
            ;
        }

        if ($this->postcodegebied) {
            $builder
                ->andWhere("{$alias}.postcodegebied = :{$alias}_postcodegebied")
                ->setParameter("{$alias}_postcodegebied", $this->postcodegebied)
            ;
        }

        if ($this->plaats) {
            $builder
                ->andWhere("{$alias}.plaats LIKE :{$alias}_plaats")
                ->setParameter("{$alias}_plaats", "%{$this->plaats}%")
            ;
        }

        if ($this->medewerker) {
            $builder
                ->andWhere("{$alias}.medewerker = :{$alias}_medewerker")
                ->setParameter("{$alias}_medewerker", $this->medewerker)
            ;
        }
    }
}
