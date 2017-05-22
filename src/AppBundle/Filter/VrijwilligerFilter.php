<?php

namespace AppBundle\Filter;

use AppBundle\Entity\Vrijwilliger;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Form\Model\AppDateRangeModel;

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

    public function applyTo(QueryBuilder $builder, $alias = 'vrijwilliger')
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

        if ($this->voornaam) {
            $parts = preg_split('/\s+/', $this->voornaam);
            foreach ($parts as $i => $part) {
                $builder
                ->andWhere("CONCAT_WS(' ', {$alias}.voornaam, {$alias}.roepnaam) LIKE :{$alias}_voornaam_part_{$i}")
                ->setParameter("{$alias}_voornaam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->achternaam) {
            $parts = preg_split('/\s+/', $this->achternaam);
            foreach ($parts as $i => $part) {
                $builder
                ->andWhere("CONCAT_WS(' ', {$alias}.tussenvoegsel, {$alias}.achternaam) LIKE :{$alias}_achternaam_part_{$i}")
                ->setParameter("{$alias}_achternaam_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->bsn) {
            $builder
                ->andWhere("{$alias}.bsn= :{$alias}_bsn")
                ->setParameter("{$alias}_bsn", $this->bsn)
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

        if (isset($this->stadsdeel)) {
            if ($this->stadsdeel == '-') {
                $builder->andWhere("{$alias}.werkgebied IS NULL OR {$alias}.werkgebied = ''");
            } else {
                $builder
                    ->andWhere("{$alias}.werkgebied = :{$alias}_stadsdeel")
                    ->setParameter("{$alias}_stadsdeel", $this->stadsdeel)
                ;
            }
        }
    }
}
