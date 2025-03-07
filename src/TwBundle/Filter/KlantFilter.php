<?php

namespace TwBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter as AppKlantFilter;
use AppBundle\Filter\NullFilterTrait;
use Doctrine\ORM\QueryBuilder;
use TwBundle\Entity\Alcohol;
use TwBundle\Entity\Dagbesteding;
use TwBundle\Entity\InschrijvingWoningnet;
use TwBundle\Entity\IntakeStatus;
use TwBundle\Entity\Klant;
use TwBundle\Entity\Project;
use TwBundle\Entity\Regio;
use TwBundle\Entity\Ritme;
use TwBundle\Entity\Roken;
use TwBundle\Entity\Softdrugs;
use TwBundle\Entity\Traplopen;

class KlantFilter implements FilterInterface
{
    use NullFilterTrait;

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_NON_ACTIVE = 'NON_ACTIVE';

    /**
     * @var int
     */
    public $id;

    /**
     * @var InschrijvingWoningnet
     */
    public $inschrijvingWoningnet;

    /**
     * @var \DateTime
     */
    public $aanmelddatum;

    /**
     * @var string
     */
    public $status;

    /**
     * @var AppKlantFilter
     */
    public $appKlant;

    /**
     * @var Medewerker
     */
    public $medewerker;

    /**
     * @var Medewerker;
     */
    public $shortlist;

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

    /** @var bool */
    public $heeftHuisgenoot;

    /** @var QueryBuilder */
    private $builder;

    public function applyTo(QueryBuilder $builder, $alias = 'klant')
    {
        if ($this->id) {
            $builder
                ->andWhere($alias.'.id = :id')
                ->setParameter('id', $this->id)
            ;
        }

        if ($this->inschrijvingWoningnet) {
            //            if ((bool) $this->inschrijvingWoningnet) {
            //                $builder->andWhere('klant.inschrijvingWoningnet = true');
            //            } else {
            //                $builder->andWhere('klant.inschrijvingWoningnet IS NULL OR klant.inschrijvingWoningnet = false');
            //            }
            $builder->andWhere($alias.'.inschrijvingWoningnet = :inschrijvingWoningnet')
                ->setParameter('inschrijvingWoningnet', $this->inschrijvingWoningnet)
            ;
        }

        if ($this->aanmelddatum) {
            if ($this->aanmelddatum->getStart()) {
                $builder
                    ->andWhere($alias.'.aanmelddatum >= :aanmelddatum_van')
                    ->setParameter('aanmelddatum_van', $this->aanmelddatum->getStart())
                ;
            }
            if ($this->aanmelddatum->getEnd()) {
                $builder
                    ->andWhere($alias.'.aanmelddatum <= :aanmelddatum_tot')
                    ->setParameter('aanmelddatum_tot', $this->aanmelddatum->getEnd())
                ;
            }
        }

        if (!is_null($this->gekoppeld)) {
            if (false == $this->gekoppeld) {// alleen ongekoppeld
                $builder->leftJoin($alias.'.huurverzoeken', 'huurverzoeken', 'WITH', '(huurverzoeken.afsluiting IS NULL)')
//                    ->orWhere('huurverzoeken IS NULL')
                    ->leftJoin('huurverzoeken.huurovereenkomst', 'huurovereenkomst')
//                    ->andWhere('huurovereenkomst IS NULL')
                    // ->leftJoin("huurverzoeken.huurovereenkomst","huurovereenkomst")
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
            } elseif (true == $this->gekoppeld) {
                $builder->leftJoin($alias.'.huurverzoeken', 'huurverzoeken')
                    ->leftJoin('huurverzoeken.huurovereenkomst', 'huurovereenkomst')
                    ->andWhere('huurovereenkomst.id IS NOT NULL
                        AND (
                            huurovereenkomst.isReservering = false AND
                            (huurovereenkomst.afsluitdatum IS NULL AND
                            huurovereenkomst.startdatum IS NOT NULL)
                            )
                    ');
            }
        }

        switch ($this->status) {
            case KlantFilter::STATUS_ACTIVE:
                $builder
                    ->andWhere($alias.'.aanmelddatum <= :today')
                    ->andWhere($alias.'.afsluitdatum > :today OR klant.afsluitdatum IS NULL')
                    ->setParameter('today', new \DateTime('today'))
                ;
                break;
            case KlantFilter::STATUS_NON_ACTIVE:
                $builder
                    ->andWhere("{$alias}.aanmelddatum IS NULL OR {$alias}.afsluitdatum <= :today")
                    ->setParameter('today', new \DateTime('today'))
                ;
                break;
            default:
                break;
        }

        if ($this->medewerker) {
            $builder->andWhere('medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker);
        }

        if ($this->heeftHuisgenoot) {
            $builder->andWhere($alias.'.huisgenoot IS NOT NULL');
        }
        if ($this->appKlant) {
            $this->appKlant->applyTo($builder, 'appKlant');
        }

        if ($this->bindingRegio && (is_array($this->bindingRegio) || $this->bindingRegio instanceof \Countable ? count($this->bindingRegio) : 0) > 0) {
            $builder->andWhere($alias.'.bindingRegio IN (:regio)')
                ->setParameter('regio', $this->bindingRegio);
        }

        if ($this->shortlist) {
            $builder->andWhere('shortlist = :shortlist')
                ->setParameter('shortlist', $this->shortlist);
        }
        if ($this->project && (is_array($this->project) || $this->project instanceof \Countable ? count($this->project) : 0) > 0) {
            $builder->andWhere('project IN (:project)')
                    ->setParameter('project', $this->project);
            //            foreach($this->project as $p)
            //            {
            //                $builder->andWhere('project = :project')
            //                    ->setParameter('project',$p);
            //            }
        }

        $this->addMultipleOrField('intakeStatus', $builder);
        // $this->addMultipleOrField('project', $builder);
        $this->addMultipleOrField('dagbesteding', $builder);
        $this->addMultipleOrField('ritme', $builder);
        $this->addMultipleOrField('huisdieren', $builder);
        $this->addMultipleOrField('roken', $builder);
        $this->addMultipleOrField('alcohol', $builder);
        $this->addMultipleOrField('softdrugs', $builder);
        $this->addMultipleOrField('traplopen', $builder);
        $this->addMultipleOrField('inkomensverklaring', $builder);
    }

    /**
     * @param $field Fieldname.
     *               Add field and (multiple) values to the filter Query.
     *               Check if is an array.
     */
    private function addMultipleOrField($field, QueryBuilder $builder)
    {
        $values = $this->$field;
        if ($values && (is_array($values) || $values instanceof \Countable ? count($values) : 0) > 0) {
            $k = array_search(0, $values, true); // 0 is de null value voor 'Onbekend'.
            if (is_int($k)) { // dit kan vast compacter maar mn hersenen zijn gaar.
                $t = array_splice($values, $k, 1);
                $builder->andWhere("(klant.$field IN (:$field) OR klant.$field IS NULL)")
                    ->setParameter($field, $values)
                ;
            } else {
                $builder->andWhere("klant.$field IN (:$field)")
                    ->setParameter($field, $values)
                ;
            }
        }
    }
}
