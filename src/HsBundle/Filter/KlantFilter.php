<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Entity\Werkgebied;

class KlantFilter implements FilterInterface
{
    const STATUS_ACTIVE = 'Actief';
    const STATUS_NON_ACTIVE = 'Niet actief';

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
