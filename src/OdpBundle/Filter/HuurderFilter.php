<?php

namespace OdpBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\FilterInterface;

class HuurderFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $automatischeIncasso;

    /**
     * @var \DateTime
     */
    public $aanmelddatum;

    /**
     * @var \DateTime
     */
    public $afsluitdatum;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var bool
     */
    public $wpi;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('huurder.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if (is_int($this->automatischeIncasso)) {
            switch ($this->automatischeIncasso) {
                case 0:
                    $builder->andWhere('huurder.automatischeIncasso IS NULL OR huurder.automatischeIncasso = :automatische_incasso');
                    break;
                case 1:
                    $builder->andWhere('huurder.automatischeIncasso = :automatische_incasso');
                    break;
            }
            $builder->setParameter('automatische_incasso', $this->automatischeIncasso);
        }

        if ($this->aanmelddatum) {
            if ($this->aanmelddatum->getStart()) {
                $builder
                    ->andWhere('huurder.aanmelddatum >= :aanmelddatum_van')
                    ->setParameter('aanmelddatum_van', $this->aanmelddatum->getStart())
                ;
            }
            if ($this->aanmelddatum->getEnd()) {
                $builder
                    ->andWhere('huurder.aanmelddatum <= :aanmelddatum_tot')
                    ->setParameter('aanmelddatum_tot', $this->aanmelddatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('huurder.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('huurder.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }

        if ($this->wpi) {
            $builder
                ->andWhere('huurder.wpi = :wpi')
                ->setParameter('wpi', $this->wpi)
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
