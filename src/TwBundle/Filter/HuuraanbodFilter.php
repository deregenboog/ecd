<?php

namespace TwBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use AppBundle\Form\Model\AppIntRangeModel;
use Doctrine\ORM\QueryBuilder;
use TwBundle\Entity\Project;

class HuuraanbodFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var AppDateRangeModel
     */
    public $startdatum;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    /**
     * @var AppDateRangeModel
     */
    public $datumToestemmingAangevraagd;

    /**
     * @var AppDateRangeModel
     */
    public $datumToestemmingToegekend;

    /**
     * @var AppIntRangeModel
     */
    public $huurprijs;

    /**
     * @var bool
     */
    public $actief = true;

    /**
     * @var HuurovereenkomstFilter
     */
    public $huurovereenkomst = true;

    /**
     * @var bool
     */
    public $isReservering;

    /**
     * @var KlantFilter
     */
    public $appKlant;


    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var Project
     */
    public $project;

    public function __construct()
    {
        $this->huurovereenkomst = new HuurovereenkomstFilter();
        $this->huurovereenkomst->isReservering = true;
    }

    public function applyTo(QueryBuilder $builder)
    {

        $this->huurovereenkomst->applyTo($builder);

        if ($this->id) {
            $builder
                ->andWhere('huuraanbod.id = :tw_klant_id')
                ->setParameter('tw_klant_id', $this->id)
            ;
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('huuraanbod.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('huuraanbod.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }
        if($this->huurprijs)
        {
            $builder->andWhere('huuraanbod.huurprijs >= :low AND huuraanbod.huurprijs <= :high')
                ->setParameter("low",$this->huurprijs->getLow())
                ->setParameter("high",$this->huurprijs->getHigh());
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('huuraanbod.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('huuraanbod.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }
        if ($this->datumToestemmingAangevraagd) {
            if ($this->datumToestemmingAangevraagd->getStart()) {
                $builder
                    ->andWhere('huuraanbod.datumToestemmingAangevraagd >= :datumToestemmingAangevraagd_van')
                    ->setParameter('datumToestemmingAangevraagd_van', $this->datumToestemmingAangevraagd->getStart())
                ;
            }
            if ($this->datumToestemmingAangevraagd->getEnd()) {
                $builder
                    ->andWhere('huuraanbod.datumToestemmingAangevraagd <= :datumToestemmingAangevraagd_tot')
                    ->setParameter('datumToestemmingAangevraagd_tot', $this->datumToestemmingAangevraagd->getEnd())
                ;
            }
        }
        if ($this->datumToestemmingToegekend) {
            if ($this->datumToestemmingToegekend->getStart()) {
                $builder
                    ->andWhere('huuraanbod.datumToestemmingToegekend >= :datumToestemmingToegekend_van')
                    ->setParameter('datumToestemmingToegekend_van', $this->datumToestemmingToegekend->getStart())
                ;
            }
            if ($this->datumToestemmingToegekend->getEnd()) {
                $builder
                    ->andWhere('huuraanbod.datumToestemmingToegekend <= :datumToestemmingToegekend_tot')
                    ->setParameter('datumToestemmingToegekend_tot', $this->datumToestemmingToegekend->getEnd())
                ;
            }
        }
        if($this->medewerker)
        {
            $builder
                ->andWhere('huuraanbod.medewerker = :medewerker')
                ->setParameter('medewerker',$this->medewerker);
        }

        if ($this->actief === true) {
            $builder
                ->andWhere('huuraanbod.afsluitdatum IS NULL OR huuraanbod.afsluitdatum > :now')
//                ->andWhere('huurovereenkomst.id IS NULL')
                ->setParameter('now', new \DateTime())
            ;
        }
//        else {
//            $builder
//                ->andWhere('huurovereenkomst.id IS NULL');
//        }

        if ($this->appKlant) {
            $this->appKlant->applyTo($builder,'appKlant');
        }
        if($this->project && (is_array($this->project) || $this->project instanceof \Countable ? count($this->project) : 0)>0)
        {
            $builder->andWhere('huuraanbod.project IN (:project)')
                ->setParameter("project",$this->project);
        }
    }
}
