<?php

namespace HsBundle\Filter;

use AppBundle\Entity\Werkgebied;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Entity\Klant;

class KlantFilter implements FilterInterface
{
    public const STATUS_ACTIVE = 'Actief';
    public const STATUS_NON_ACTIVE = 'Niet actief';
    public const STATUS_GEEN_NIEUWE_KLUSSEN = 'Geen nieuwe klussen';

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
    public $hulpverlener;

    /**
     * @var string
     */
    public $adres;

    /**
     * @var Werkgebied
     */
    public $stadsdeel;

    /**
     * @var string
     */
    public $status;

    /**
     * @var bool
     */
    public $negatiefSaldo;

    /**
     * @var int
     */
    public $afwijkendFactuuradres;

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
                    ->andWhere("CONCAT_WS(' ', {$this->alias}.voornaam, {$this->alias}.tussenvoegsel, {$this->alias}.achternaam) LIKE :{$this->alias}_naam_part_{$i}")
                    ->setParameter("{$this->alias}_naam_part_{$i}", "%{$part}%")
                ;
            }
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

        if ($this->adres) {
            $parts = preg_split('/\s+/', $this->adres);
            $fields = ["{$this->alias}.adres", "{$this->alias}.postcode", "{$this->alias}.plaats", "{$this->alias}.telefoon", "{$this->alias}.mobiel"];
            foreach ($parts as $i => $part) {
                $builder
                    ->andWhere("CONCAT_WS(' ', ".implode(', ', $fields).") LIKE :{$this->alias}_adres_part_{$i}")
                    ->setParameter("{$this->alias}_adres_part_{$i}", "%{$part}%")
                ;
            }
        }

        if ($this->stadsdeel) {
            $builder
                ->andWhere("{$this->alias}.werkgebied = :{$this->alias}_stadsdeel")
                ->setParameter("{$this->alias}_stadsdeel", $this->stadsdeel)
            ;
        }

        if ($this->status) {
            switch ($this->status) {
                case self::STATUS_ACTIVE:
                    $builder->andWhere("{$this->alias}.actief = true");
                    break;
                case self::STATUS_NON_ACTIVE:
                    $builder->andWhere("{$this->alias}.actief = false");
                    break;
                case self::STATUS_GEEN_NIEUWE_KLUSSEN:
                    $builder->setParameter(':status', Klant::STATUS_GEEN_NIEUWE_KLUS);
                    $builder->andWhere("{$this->alias}.status = :status");
                    // no break
                default:
                    break;
            }
        }

        if (null !== $this->afwijkendFactuuradres) {
            $builder
                ->andWhere("{$this->alias}.afwijkendFactuuradres = :afwijkendFactuuradres")
                ->setParameter('afwijkendFactuuradres', (bool) $this->afwijkendFactuuradres)
            ;
        }

        if ($this->negatiefSaldo) {
            $builder->andWhere("{$this->alias}.saldo < 0");
        }
    }
}
