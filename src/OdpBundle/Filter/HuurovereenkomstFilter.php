<?php

namespace OdpBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use OdpBundle\Entity\Project;

class HuurovereenkomstFilter implements FilterInterface
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
    public $opzegdatum;

    /**
     * @var AppDateRangeModel
     */
    public $einddatum;

    /**
     * @var string
     */
    public $vorm;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitdatum;

    /**
     * @var bool
     */
    public $actief = true;

    /**
     * @var bool
     */
    public $isReservering;

    /**
     * @var bool
     */
    public $opzegbriefVerstuurd;


    /**
     * @var HuurderFilter
     */
    public $huurder;

    /**
     * @var KlantFilter
     */
    public $verhuurderKlant;

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
                ->andWhere('huurovereenkomst.id = :huurovereenkomst_id')
                ->setParameter('huurovereenkomst_id', $this->id)
            ;
        }

        if ($this->startdatum) {
            if ($this->startdatum->getStart()) {
                $builder
                    ->andWhere('huurovereenkomst.startdatum >= :startdatum_van')
                    ->setParameter('startdatum_van', $this->startdatum->getStart())
                ;
            }
            if ($this->startdatum->getEnd()) {
                $builder
                    ->andWhere('huurovereenkomst.startdatum <= :startdatum_tot')
                    ->setParameter('startdatum_tot', $this->startdatum->getEnd())
                ;
            }
        }

        if ($this->opzegdatum) {
            if ($this->opzegdatum->getStart()) {
                $builder
                    ->andWhere('huurovereenkomst.opzegdatum >= :opzegdatum_van')
                    ->setParameter('opzegdatum_van', $this->opzegdatum->getStart())
                ;
            }
            if ($this->opzegdatum->getEnd()) {
                $builder
                    ->andWhere('huurovereenkomst.opzegdatum <= :opzegdatum_tot')
                    ->setParameter('opzegdatum_tot', $this->opzegdatum->getEnd())
                ;
            }
        }

        if ($this->einddatum) {
            if ($this->einddatum->getStart() && !$this->einddatum->getEnd()) {
                $builder
                    ->andWhere('huurovereenkomst.einddatum >= :einddatum_van OR huurovereenkomst.einddatum IS NULL')
                    ->setParameter('einddatum_van', $this->einddatum->getStart())
                ;
            }
            elseif($this->einddatum->getStart())
            {
                $builder
                    ->andWhere('huurovereenkomst.einddatum >= :einddatum_van')
                    ->setParameter('einddatum_van', $this->einddatum->getStart())
                ;
            }
            if ($this->einddatum->getEnd()) {
                $builder
                    ->andWhere('huurovereenkomst.einddatum <= :einddatum_tot')
                    ->setParameter('einddatum_tot', $this->einddatum->getEnd())
                ;
            }

        }

        if ($this->vorm) {
            $builder
                ->andWhere('huurovereenkomst.vorm = :vorm')
                ->setParameter('vorm', $this->vorm)
            ;
        }

        if ($this->afsluitdatum) {
            if ($this->afsluitdatum->getStart()) {
                $builder
                    ->andWhere('huurovereenkomst.afsluitdatum >= :afsluitdatum_van')
                    ->setParameter('afsluitdatum_van', $this->afsluitdatum->getStart())
                ;
            }
            if ($this->afsluitdatum->getEnd()) {
                $builder
                    ->andWhere('huurovereenkomst.afsluitdatum <= :afsluitdatum_tot')
                    ->setParameter('afsluitdatum_tot', $this->afsluitdatum->getEnd())
                ;
            }
        }
        if (!$this->isReservering) {
            $builder
                ->andWhere('huurovereenkomst.isReservering = 0  OR huurovereenkomst IS NULL')
            ;
        }
        else if($this->isReservering == true)
        {
            //INCLUDES reserveringen, not ONLY reserveringen.
            $builder
                //->andWhere('huurovereenkomst.isReservering IS NULL OR huurovereenkomst.isReservering = 0')
                ->orWhere('huurovereenkomst.isReservering = 1')
            ;
        }

        if ($this->actief) {
            $builder
                ->andWhere('huurovereenkomst.afsluitdatum IS NULL OR huurovereenkomst.afsluitdatum > :now')
                ->setParameter('now', new \DateTime())
            ;
        }
        if ($this->opzegbriefVerstuurd) {
            $builder
                ->andWhere('huurovereenkomst.opzegbriefVerstuurd = true')

            ;
        }

        if ($this->huurder) {
            $this->huurder->applyTo($builder);
        }

        if ($this->verhuurderKlant) {
            $this->verhuurderKlant->applyTo($builder, 'verhuurderKlant');
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('huurovereenkomst.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }
        if($this->project)
        {
            $builder->andWhere('huuraanbod.project = :project')
                ->setParameter("project",$this->project);
        }
    }
}
