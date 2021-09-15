<?php

namespace TwBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use Doctrine\ORM\QueryBuilder;
use TwBundle\Entity\Project;

class KlantFilter implements FilterInterface
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
     * @var AppKlantFilter
     */
    public $appKlant;

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

    /**
     * @var Project
     */
    public $project;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('klant.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if (is_int($this->automatischeIncasso)) {
            if ((bool) $this->automatischeIncasso) {
                $builder->andWhere('klant.automatischeIncasso = true');
            } else {
                $builder->andWhere('klant.automatischeIncasso IS NULL OR klant.automatischeIncasso = false');
            }
        }

        if (is_int($this->inschrijvingWoningnet)) {
            if ((bool) $this->inschrijvingWoningnet) {
                $builder->andWhere('klant.inschrijvingWoningnet = true');
            } else {
                $builder->andWhere('klant.inschrijvingWoningnet IS NULL OR klant.inschrijvingWoningnet = false');
            }
        }

        if (is_int($this->waPolis)) {
            if ((bool) $this->waPolis) {
                $builder->andWhere('klant.waPolis = true');
            } else {
                $builder->andWhere('klant.waPolis IS NULL OR klant.waPolis = false');
            }
        }

        if ($this->aanmelddatum) {
            if ($this->aanmelddatum->getStart()) {
                $builder
                    ->andWhere('klant.aanmelddatum >= :aanmelddatum_van')
                    ->setParameter('aanmelddatum_van', $this->aanmelddatum->getStart())
                ;
            }
            if ($this->aanmelddatum->getEnd()) {
                $builder
                    ->andWhere('klant.aanmelddatum <= :aanmelddatum_tot')
                    ->setParameter('aanmelddatum_tot', $this->aanmelddatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('klant.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('klant.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }

        if ($this->actief) {

            $builder
                ->andWhere('klant.aanmelddatum <= :today')
                 ->andWhere('klant.afsluitdatum > :today OR klant.afsluitdatum IS NULL')
                 ->setParameter('today', new \DateTime('today'))
            ;
        }

        if ($this->wpi) {
            $builder
                ->andWhere('klant.wpi = :wpi')
                ->setParameter('wpi', $this->wpi)
            ;
        }
        if($this->medewerker)
        {
            $builder->andWhere('medewerker = :medewerker')
                ->setParameter('medewerker',$this->medewerker);
        }
        if($this->project)
        {
            $builder->innerJoin('klant.projecten', 'project')
                ->andWhere('project.id = :project')
                ->setParameter("project",$this->project);
        }
        if($this->ambulantOndersteuner)
        {

            $builder
//                ->leftJoin('klant.ambulantOndersteuner','ambulantOndersteuner')
//                ->andWhere('ambulantOndersteuner IS NULL')
                ->andWhere('ambulantOndersteuner = :ambulantOndersteuner')

                ->setParameter('ambulantOndersteuner',$this->ambulantOndersteuner);
        }


        if ($this->appKlant) {
            $this->appKlant->applyTo($builder,'appKlant');
        }
    }
}
