<?php

namespace TwBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use Doctrine\ORM\QueryBuilder;
use TwBundle\Entity\Alcohol;
use TwBundle\Entity\Dagbesteding;
use TwBundle\Entity\InschrijvingWoningnet;
use TwBundle\Entity\Intake;
use TwBundle\Entity\IntakeStatus;
use TwBundle\Entity\Project;
use TwBundle\Entity\Regio;
use TwBundle\Entity\Ritme;
use TwBundle\Entity\Roken;
use TwBundle\Entity\Softdrugs;
use TwBundle\Entity\Traplopen;

class KlantFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

//    /**
//     * @var int
//     */
//    public $automatischeIncasso;
//
    /**
     * @var InschrijvingWoningnet
     */
    public $inschrijvingWoningnet;

//
//    /**
//     * @var int
//     */
//    public $waPolis;

    /**
     * @var \DateTime
     */
    public $aanmelddatum;

//    /**
//     * @var \DateTime
//     */
//    public $afsluitdatum;

    /**
     * @var bool
     */
    public $actief;

    /**
     * @var AppKlantFilter
     */
    public $appKlant;

//    /**
//     * @var bool
//     */
//    public $wpi;

    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var Medewerker;
     */
    public $shortlist;
//
//    /**
//     * @var Medewerker
//     */
//    public $ambulantOndersteuner;

    /**
     * @var IntakeStatus
     */
    public $intakeStatus;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var bool
     */
    public $gekoppeld;

    /**
     * @var Regio
     */
    public $bindingRegio;

    /**
     * @var Dagbesteding
     */
    public $dagbesteding;

    /**
     * @var Ritme
     */
    public $ritme;

    /**
     * @var Huisdieren
     *
     */
    public $huisdieren;

    /**
     * @var Roken
     */
    public $roken;

    /**
     * @var Softdrugs
     */
    public $softdrugs;

    /**
     * @var Alcohol
     */
    public $alcohol;

    /**
     * @var bool|null
     */
    public $inkomensverklaring;

    /**
     * @var Traplopen
     */
    public $traplopen;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->id) {
            $builder
                ->andWhere('klant.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

//        if (is_int($this->automatischeIncasso)) {
//            if ((bool) $this->automatischeIncasso) {
//                $builder->andWhere('klant.automatischeIncasso = true');
//            } else {
//                $builder->andWhere('klant.automatischeIncasso IS NULL OR klant.automatischeIncasso = false');
//            }
//        }
//
        if ($this->inschrijvingWoningnet) {
//            if ((bool) $this->inschrijvingWoningnet) {
//                $builder->andWhere('klant.inschrijvingWoningnet = true');
//            } else {
//                $builder->andWhere('klant.inschrijvingWoningnet IS NULL OR klant.inschrijvingWoningnet = false');
//            }
            $builder->andWhere('klant.inschrijvingWoningnet = :inschrijvingWoningnet')
                ->setParameter("inschrijvingWoningnet",$this->inschrijvingWoningnet)
                ;
        }
//
//        if (is_int($this->waPolis)) {
//            if ((bool) $this->waPolis) {
//                $builder->andWhere('klant.waPolis = true');
//            } else {
//                $builder->andWhere('klant.waPolis IS NULL OR klant.waPolis = false');
//            }
//        }

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

//        if ($this->afsluitdatum) {
//            if ($this->afsluitdatum->getStart()) {
//                $builder
//                    ->andWhere('klant.afsluitdatum >= :afsluitdatum_van')
//                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
//                ;
//            }
//            if ($this->afsluitdatum->getEnd()) {
//                $builder
//                    ->andWhere('klant.afsluitdatum <= :afsluitdatum_tot')
//                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
//                ;
//            }
//        }

        if(!is_null($this->gekoppeld))
        {
            if($this->gekoppeld == false)//alleen ongekoppeld
            {
                $builder->leftJoin("klant.huurverzoeken","huurverzoeken")
//                    ->orWhere('huurverzoeken IS NULL')
                    ->leftJoin('huurverzoeken.huurovereenkomst',"huurovereenkomst")
//                    ->andWhere('huurovereenkomst IS NULL')
                    //->leftJoin("huurverzoeken.huurovereenkomst","huurovereenkomst")
                    ->andWhere('(huurovereenkomst IS NULL 
                        OR huurverzoeken IS NULL 
                        OR
                        (
                            huurovereenkomst IS NOT NULL
                            AND huurovereenkomst.isReservering = true
                        )
                    )
                    ')
                    ;
            }
            elseif($this->gekoppeld ==true)
            {
                $builder->leftJoin("klant.huurverzoeken","huurverzoeken")
                    ->leftJoin("huurverzoeken.huurovereenkomst","huurovereenkomst")
                    ->andWhere("huurovereenkomst.id IS NOT NULL
                        AND (
                            huurovereenkomst.isReservering = false AND
                            (huurovereenkomst.afsluitdatum IS NULL AND
                            huurovereenkomst.startdatum IS NOT NULL)
                            )
                    ");
            }

//            ->setParameter("gekoppeld",$this->gekoppeld)
            ;
        }

        if ($this->actief) {

            $builder
                ->andWhere("klant.aanmelddatum <= :today')
                 ->andWhere('klant.afsluitdatum > :today OR klant.afsluitdatum IS NULL')
                 ->setParameter('today', new \DateTime('today')")
            ;
        }

//        if ($this->wpi) {
//            $builder
//                ->andWhere('klant.wpi = :wpi')
//                ->setParameter('wpi', $this->wpi)
//            ;
//        }
        if($this->medewerker)
        {
            $builder->andWhere('medewerker = :medewerker')
                ->setParameter('medewerker',$this->medewerker);
        }


        if($this->intakeStatus && count($this->intakeStatus)>0)
        {
            $builder->andWhere('klant.intakeStatus = :intakeStatus')
                ->setParameter('intakeStatus',$this->intakeStatus)
                ;
        }

        if($this->project)
        {
            $builder->innerJoin('klant.projecten', 'project')
                ->andWhere('project.id = :project')
                ->setParameter("project",$this->project);
        }
//        if($this->ambulantOndersteuner)
//        {
//
//            $builder
////                ->leftJoin('klant.ambulantOndersteuner','ambulantOndersteuner')
////                ->andWhere('ambulantOndersteuner IS NULL')
//                ->andWhere('ambulantOndersteuner = :ambulantOndersteuner')
//
//                ->setParameter('ambulantOndersteuner',$this->ambulantOndersteuner);
//        }


        if ($this->appKlant) {
            $this->appKlant->applyTo($builder,'appKlant');
        }

        if($this->dagbesteding && count($this->dagbesteding) > 0)
        {
            $builder->andWhere('klant.dagbesteding IN (:dagbesteding)')
                ->setParameter("dagbesteding",$this->dagbesteding)
            ;
        }

        if($this->ritme && count($this->ritme) >0)
        {
            $builder->andWhere('klant.ritme IN (:ritme)')
                ->setParameter("ritme",$this->ritme)
            ;
        }

        if($this->huisdieren && count($this->huisdieren))
        {
            $builder->andWhere('klant.huisdieren IN (:huisdieren)')
                ->setParameter("huisdieren",$this->huisdieren)
            ;
        }

        if($this->roken && count($this->roken))
        {
            $builder->andWhere('klant.roken IN(:roken)')
                ->setParameter("roken",$this->roken)
            ;
        }

        if($this->softdrugs && count($this->softdrugs))
        {
            $builder->andWhere('klant.softdrugs IN (:softdrugs)')
                ->setParameter("softdrugs",$this->softdrugs)
            ;
        }

        if($this->traplopen && count($this->traplopen))
        {
            $builder->andWhere('klant.traplopen IN (:traplopen)')
                ->setParameter("traplopen",$this->traplopen)
            ;
        }

        if($this->inkomensverklaring)
        {
            $builder->andWhere('klant.inkomensverklaring IN (:inkomensverklaring)')
                ->setParameter("inkomensverklaring",$this->inkomensverklaring)
            ;
        }

    }
}
