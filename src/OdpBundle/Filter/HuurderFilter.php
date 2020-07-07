<?php

namespace OdpBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\QueryBuilder;

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
     * @var int
     */
    public $inschrijvingWoningnet;

    /**
     * @var int
     */
    public $waPolis;

    /**
     * @var \DateTime
     */
    public $aanmelddatum;

    /**
     * @var \DateTime
     */
    public $afsluitdatum;

    /**
     * @var bool
     */
    public $actief;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var bool
     */
    public $wpi;

    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var Medewerker
     */
    public $ambulantOndersteuner;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('huurder.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if (is_int($this->automatischeIncasso)) {
            if ((bool) $this->automatischeIncasso) {
                $builder->andWhere('huurder.automatischeIncasso = true');
            } else {
                $builder->andWhere('huurder.automatischeIncasso IS NULL OR huurder.automatischeIncasso = false');
            }
        }

        if (is_int($this->inschrijvingWoningnet)) {
            if ((bool) $this->inschrijvingWoningnet) {
                $builder->andWhere('huurder.inschrijvingWoningnet = true');
            } else {
                $builder->andWhere('huurder.inschrijvingWoningnet IS NULL OR huurder.inschrijvingWoningnet = false');
            }
        }

        if (is_int($this->waPolis)) {
            if ((bool) $this->waPolis) {
                $builder->andWhere('huurder.waPolis = true');
            } else {
                $builder->andWhere('huurder.waPolis IS NULL OR huurder.waPolis = false');
            }
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

        if ($this->actief) {

            $builder
                ->andWhere('huurder.aanmelddatum <= :today')
                 ->andWhere('huurder.afsluitdatum > :today OR huurder.afsluitdatum IS NULL')
                 ->setParameter('today', new \DateTime('today'))
            ;
        }

        if ($this->wpi) {
            $builder
                ->andWhere('huurder.wpi = :wpi')
                ->setParameter('wpi', $this->wpi)
            ;
        }
        if($this->medewerker)
        {
            $builder->andWhere('medewerker = :medewerker')
                ->setParameter('medewerker',$this->medewerker);
        }
        if($this->ambulantOndersteuner)
        {

            $builder
//                ->leftJoin('huurder.ambulantOndersteuner','ambulantOndersteuner')
//                ->andWhere('ambulantOndersteuner IS NULL')
                ->andWhere('ambulantOndersteuner = :ambulantOndersteuner')

                ->setParameter('ambulantOndersteuner',$this->ambulantOndersteuner);
        }


        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
