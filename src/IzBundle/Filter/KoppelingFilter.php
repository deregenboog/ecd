<?php

namespace IzBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\VrijwilligerFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Doelgroep;
use IzBundle\Entity\Hulpvraagsoort;
use IzBundle\Entity\Project;

class KoppelingFilter implements FilterInterface
{
    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

    /**
     * @var AppDateRangeModel
     */
    public $koppelingStartdatum;

    /**
     * @var AppDateRangeModel
     */
    public $koppelingEinddatum;

    /**
     * @var bool
     */
    public $lopendeKoppelingen = true;

    /**
     * @var bool
     */
    public $langeKoppelingen = false;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var Hulpvraagsoort
     */
    public $hulpvraagsoort;

    /**
     * @var Doelgroep
     */
    public $doelgroep;

    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var Medewerker
     */
    public $hulpvraagMedewerker;

    /**
     * @var Medewerker
     */
    public $hulpaanbodMedewerker;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }

        if ($this->koppelingStartdatum) {
            if ($this->koppelingStartdatum->getStart()) {
                $builder
                    ->andWhere('hulpvraag.koppelingStartdatum >= :koppelingStartdatum_van')
                    ->setParameter('koppelingStartdatum_van', $this->koppelingStartdatum->getStart())
                ;
            }
            if ($this->koppelingStartdatum->getEnd()) {
                $builder
                    ->andWhere('hulpvraag.koppelingStartdatum <= :koppelingStartdatum_tot')
                    ->setParameter('koppelingStartdatum_tot', $this->koppelingStartdatum->getEnd())
                ;
            }
        }

        if ($this->koppelingEinddatum) {
            if ($this->koppelingEinddatum->getStart()) {
                $builder
                    ->andWhere('hulpvraag.koppelingEinddatum >= :koppelingEinddatum_van')
                    ->setParameter('koppelingEinddatum_van', $this->koppelingEinddatum->getStart())
                ;
            }
            if ($this->koppelingEinddatum->getEnd()) {
                $builder
                    ->andWhere('hulpvraag.koppelingEinddatum <= :koppelingEinddatum_tot')
                    ->setParameter('koppelingEinddatum_tot', $this->koppelingEinddatum->getEnd())
                ;
            }
        }

        if ($this->lopendeKoppelingen) {
            $builder
                ->andWhere('hulpvraag.koppelingEinddatum IS NULL OR hulpvraag.koppelingEinddatum > :now')
                ->setParameter('now', new \DateTime())
            ;
        }

        if ($this->langeKoppelingen) {
            $builder
                ->andWhere($builder->expr()->orX(
                    'hulpvraag.koppelingEinddatum IS NULL AND hulpvraag.koppelingStartdatum < :six_months_ago',
                    'DATEDIFF(hulpvraag.koppelingEinddatum, hulpvraag.koppelingStartdatum) >= 183'
                ))
                ->setParameter('six_months_ago', new \DateTime('-6 months'))
            ;
        }

        if ($this->project) {
            $builder
                ->andWhere('hulpvraag.project = :project')
                ->setParameter('project', $this->project)
            ;
        }

        if ($this->hulpvraagsoort) {
            if (!in_array('hulpvraagsoort', $builder->getAllAliases())) {
                $builder->innerJoin('hulpvraag.hulpvraagsoort', 'hulpvraagsoort');
            }
            $builder
                ->andWhere('hulpvraagsoort = :hulpvraagsoort')
                ->setParameter('hulpvraagsoort', $this->hulpvraagsoort)
            ;
        }

        if ($this->doelgroep) {
            if (!in_array('doelgroep', $builder->getAllAliases())) {
                $builder->innerJoin('hulpvraag.doelgroepen', 'doelgroep');
            }
            $builder
                ->andWhere('doelgroep = :doelgroep')
                ->setParameter('doelgroep', $this->doelgroep)
            ;
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('hulpvraag.medewerker = :medewerker OR hulpaanbod.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }

        if ($this->hulpvraagMedewerker) {
            $builder
                ->andWhere('hulpvraag.medewerker = :hulpvraagMedewerker')
                ->setParameter('hulpvraagMedewerker', $this->hulpvraagMedewerker)
            ;
        }

        //        if ($this->hulpaanbodMedewerker) {
        //            $builder
        //                ->andWhere('hulpaanbod.medewerker = :hulpaanbodMedewerker')
        //                ->setParameter('hulpaanbodMedewerker', $this->hulpaanbodMedewerker)
        //            ;
        //        }
    }
}
