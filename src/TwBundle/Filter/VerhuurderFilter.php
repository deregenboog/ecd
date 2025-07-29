<?php

namespace TwBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\QueryBuilder;
use TwBundle\Entity\Project;

class VerhuurderFilter implements FilterInterface
{
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_NON_ACTIVE = 'NON_ACTIVE';

    /**
     * @var int
     */
    public $id;

    /**
     * @var \DateTime
     */
    public $aanmelddatum;

    /**
     * @var \DateTime
     */
    public $afsluitdatum;

    /**
     * @var string
     */
    public $status;

    /**
     * @var bool
     */
    public $gekoppeld = null;

    /**
     * @var KlantFilter
     */
    public $appKlant;

    /**
     * @var Medewerker
     */
    public $ambulantOndersteuner;

    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var Project
     */
    public $project;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('verhuurder.id = :id')
                ->setParameter('id', $this->id)
            ;
        }
        if ($this->project && (is_array($this->project) || $this->project instanceof \Countable ? count($this->project) : 0) > 0) {
            $builder
                ->andWhere('verhuurder.project IN (:project)')
                ->setParameter('project', $this->project);
        }
        if ($this->aanmelddatum) {
            if ($this->aanmelddatum->getStart()) {
                $builder
                    ->andWhere('verhuurder.aanmelddatum >= :aanmelddatum_van')
                    ->setParameter('aanmelddatum_van', $this->aanmelddatum->getStart())
                ;
            }
            if ($this->aanmelddatum->getEnd()) {
                $builder
                    ->andWhere('verhuurder.aanmelddatum <= :aanmelddatum_tot')
                    ->setParameter('aanmelddatum_tot', $this->aanmelddatum->getEnd())
                ;
            }
        }

        switch ($this->status) {
            case VerhuurderFilter::STATUS_ACTIVE:
                $builder
                    ->andWhere('verhuurder.aanmelddatum <= :today')
                    ->andWhere('verhuurder.afsluitdatum > :today OR verhuurder.afsluitdatum IS NULL')
                    ->setParameter('today', new \DateTime('today'))
                ;
                break;
            case VerhuurderFilter::STATUS_NON_ACTIVE:
                $builder
                    ->andWhere('verhuurder.aanmelddatum IS NULL OR verhuurder.afsluitdatum <= :today')
                    ->setParameter('today', new \DateTime('today'))
                ;
                break;
            default:
                break;
        }

        if (!is_null($this->gekoppeld)) {
            $builder
                ->leftJoin(
                    'verhuurder.huuraanbiedingen', 
                    'huuraanbod', 
                    'WITH', 
                    'huuraanbod.afsluiting IS NULL'
                )
                ->leftJoin(
                    'huuraanbod.huurovereenkomst',
                    'huurovereenkomst',
                    'WITH',
                    'huurovereenkomst.isReservering = FALSE
                        AND huurovereenkomst.startdatum IS NOT NULL
                        AND 
                        (
                            huurovereenkomst.startdatum <= :today
                            OR 
                            (
                                huurovereenkomst.einddatum IS NULL 
                                OR huurovereenkomst.einddatum >= :today
                            )
                        )',
                )
                ->setParameter('today', new \DateTime('today'))
            ;

            if (true === $this->gekoppeld) {
                $builder->andWhere('huurovereenkomst IS NOT NULL');
            } else {
                $builder->andWhere('huurovereenkomst IS NULL');
            }
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('verhuurder.medewerker = :medewerker')

                ->setParameter('medewerker', $this->medewerker);
        }

        if ($this->appKlant) {
            $this->appKlant->applyTo($builder, 'appKlant');
        }
    }
}
