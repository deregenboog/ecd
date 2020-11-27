<?php

namespace OdpBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use OdpBundle\Entity\Project;

class HuurverzoekFilter implements FilterInterface
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
     * @var HuurovereenkomstFilter
     */
    public $huurovereenkomst;

    /**
     * @var bool
     */
    public $actief;

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

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('huurverzoek.id = :odp_klant_id')
                ->setParameter('odp_klant_id', $this->id)
            ;
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('huurverzoek.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('huurverzoek.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('huurverzoek.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('huurverzoek.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }
        if($this->medewerker)
        {
            $builder

                ->andWhere('huurverzoek.medewerker = :medewerker')
                ->setParameter('medewerker',$this->medewerker);
        }
        if ($this->huurovereenkomst) {
            $this->huurovereenkomst->applyTo($builder);
        }

        if ($this->actief) {
            $builder
                ->andWhere('huurverzoek.afsluitdatum IS NULL OR huurverzoek.afsluitdatum > :now')
                ->setParameter('now', new \DateTime())
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if($this->project)
        {
            $builder->innerJoin('huurverzoek.projecten', 'project')
                ->andWhere('project.id = :project')
                ->setParameter("project",$this->project);
        }
    }
}
