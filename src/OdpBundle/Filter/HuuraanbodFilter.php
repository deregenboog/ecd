<?php

namespace OdpBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use OdpBundle\Entity\Project;

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
     * @var bool
     */
    public $actief;

    /**
     * @var HuurovereenkomstFilter
     */
    public $huurovereenkomst;

    /**
     * @var bool
     */
    public $isReservering;

    /**
     * @var KlantFilter
     */
    public $klant;


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
//        $this->huurovereenkomst->isReservering = true;
    }

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('huuraanbod.id = :odp_klant_id')
                ->setParameter('odp_klant_id', $this->id)
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

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('huuraanbod.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluit->getEnd()) {
                $builder
                    ->andWhere('huuraanbod.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
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
                ->andWhere('huurovereenkomst.id IS NOT NULL')
                ->setParameter('now', new \DateTime())
            ;
        }
        else {
            $builder
                ->andWhere('huurovereenkomst.id IS NULL');
        }
        if ($this->huurovereenkomst) {
            $this->huurovereenkomst->applyTo($builder);
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
        if($this->project)
        {
            $builder->andWhere('huuraanbod.project = :project')
                ->setParameter("project",$this->project);
        }
    }
}
