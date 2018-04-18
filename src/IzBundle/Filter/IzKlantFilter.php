<?php

namespace IzBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Deelnemerstatus;
use IzBundle\Entity\Project;

class IzKlantFilter implements FilterInterface
{
    const ACTIEF_NU = 'nu';
    const ACTIEF_OOIT = 'ooit';

    /**
     * @var string
     */
    public $status;

    /**
     * @var AppDateRangeModel
     */
    public $datumAanmelding;

    /**
     * @var AppDateRangeModel
     */
    public $afsluitDatum;

    /**
     * @var bool
     */
    public $openDossiers;

    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var string
     */
    public $actief;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var Medewerker
     */
    public $intakeMedewerker;

    /**
     * @var Medewerker
     */
    public $hulpvraagMedewerker;

    /**
     * @var bool
     */
    public $zonderActieveHulpvraag;

    /**
     * @var bool
     */
    public $zonderActieveKoppeling;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->status) {
            if ('Kan gekoppeld worden' === $this->status) {
                // met open hulpvraag
                $builder->andWhere('koppeling.id IS NULL');
            } else {
                $builder
                    ->andWhere('deelnemerstatus.naam = :statusnaam OR koppelingstatus.naam = :statusnaam')
                    ->setParameter('statusnaam', $this->status)
                ;
            }
        }

        if ($this->datumAanmelding) {
            if ($this->datumAanmelding->getStart()) {
                $builder
                    ->andWhere('izKlant.datumAanmelding >= :izKlant_datumAanmelding_van')
                    ->setParameter('izKlant_datumAanmelding_van', $this->datumAanmelding->getStart())
                ;
            }
            if ($this->datumAanmelding->getEnd()) {
                $builder
                    ->andWhere('izKlant.datumAanmelding <= :izKlant_datumAanmelding_tot')
                    ->setParameter('izKlant_datumAanmelding_tot', $this->datumAanmelding->getEnd())
                ;
            }
        }

        if ($this->afsluitDatum) {
            if ($this->afsluitDatum->getStart()) {
                $builder
                    ->andWhere('izKlant.afsluitDatum >= :izKlant_afsluitDatum_van')
                    ->setParameter('izKlant_afsluitDatum_van', $this->afsluitDatum->getStart())
                ;
            }
            if ($this->afsluitDatum->getEnd()) {
                $builder
                    ->andWhere('izKlant.afsluitDatum <= :izKlant_afsluitDatum_tot')
                    ->setParameter('izKlant_afsluitDatum_tot', $this->afsluitDatum->getEnd())
                ;
            }
        }

        if ($this->openDossiers) {
            $builder
                ->andWhere('izKlant.afsluitDatum IS NULL OR izKlant.afsluitDatum > :now')
                ->setParameter('now', new \DateTime())
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }

        if ($this->project) {
            switch ($this->actief) {
                case self::ACTIEF_OOIT:
                    $builder
                        ->andWhere('hulpvraag.project = :project')
                        ->setParameter('project', $this->project)
                    ;
                    break;
                case self::ACTIEF_NU:
                default:
                    $builder
                        ->andWhere('hulpvraag.project = :project')
                        ->andWhere('hulpvraag.einddatum IS NULL')
                        ->andWhere('koppeling.afsluitdatum IS NULL')
                        ->setParameter('project', $this->project)
                    ;
                    break;
            }
        }

        if ($this->intakeMedewerker) {
            $builder
                ->andWhere('intakeMedewerker = :intakeMedewerker')
                ->setParameter('intakeMedewerker', $this->intakeMedewerker)
            ;
        }

        if ($this->hulpvraagMedewerker) {
            $builder
                ->andWhere('hulpvraagMedewerker = :hulpvraagMedewerker')
                ->setParameter('hulpvraagMedewerker', $this->hulpvraagMedewerker)
            ;
        }

        if ($this->zonderActieveHulpvraag) {
            $subBuilder = $this->getSubBuilder($builder)
                ->select('izKlant.id')
                ->innerJoin('izKlant.hulpvragen', 'hulpvraag')
                ->leftJoin('hulpvraag.koppeling', 'koppeling')
                ->andWhere($builder->expr()->andX(
                    'koppeling.id IS NULL',
                    'hulpvraag.einddatum IS NULL OR hulpvraag.einddatum >= :now'
                ))
                ->addGroupBy('izKlant.id')
                ->setParameter('now', new \DateTime())
            ;

            $metActieveHulpvraag = $this->getIds($subBuilder);
            if ($metActieveHulpvraag) {
                $builder
                    ->andWhere('izKlant.id NOT IN (:met_actieve_hulpvraag)')
                    ->setParameter('met_actieve_hulpvraag', $metActieveHulpvraag)
                ;
            }
        }

        if ($this->zonderActieveKoppeling) {
            $subBuilder = $this->getSubBuilder($builder)
                ->select('izKlant.id')
                ->innerJoin('izKlant.hulpvragen', 'hulpvraag')
                ->innerJoin('hulpvraag.koppeling', 'koppeling')
                ->andWhere($builder->expr()->andX(
                    'koppeling.id IS NOT NULL',
                    'koppeling.afsluitdatum IS NULL OR koppeling.afsluitdatum >= :now'
                ))
                ->addGroupBy('izKlant.id')
                ->setParameter('now', new \DateTime())
            ;

            $metActieveKoppeling = $this->getIds($subBuilder);
            if ($metActieveKoppeling) {
                $builder
                    ->andWhere('izKlant.id NOT IN (:met_actieve_koppeling)')
                    ->setParameter('met_actieve_koppeling', $metActieveKoppeling)
                ;
            }
        }
    }

    private function getSubBuilder(QueryBuilder $builder)
    {
        $subBuilder = clone $builder;

        // keep select and from parts, reset the rest
        $dqlParts = $subBuilder->getDQLParts();
        unset($dqlParts['select']);
        unset($dqlParts['from']);
        $subBuilder->resetDQLParts(array_keys($dqlParts))->setParameters([]);

        return $subBuilder;
    }

    private function getIds(QueryBuilder $builder)
    {
        return array_map(
            function (array $item) {
                return $item['id'];
            },
            $builder->getQuery()->getResult()
        );
    }
}
