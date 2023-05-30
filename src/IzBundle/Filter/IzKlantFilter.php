<?php

namespace IzBundle\Filter;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use AppBundle\Form\Model\AppDateRangeModel;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Doelgroep;
use IzBundle\Entity\Hulp;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Hulpvraagsoort;
use IzBundle\Entity\IzDeelnemer;
use IzBundle\Entity\Koppeling;
use IzBundle\Entity\Project;
use ShipMonk\Doctrine\MySql\IndexHint;
use ShipMonk\Doctrine\MySql\UseIndexSqlWalker;

class IzKlantFilter implements FilterInterface
{
    public const ACTIEF_NU = 'nu';
    public const ACTIEF_OOIT = 'ooit';

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
    public $aanmeldingMedewerker;

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
                        ->andWhere('hulpvraag.koppelingEinddatum IS NULL')
                        ->setParameter('project', $this->project)
                    ;
                    break;
            }
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
        if ($this->aanmeldingMedewerker) {
            $builder
                ->andWhere('aanmeldingMedewerker = :aanmeldingMedewerker')
                ->setParameter('aanmeldingMedewerker', $this->aanmeldingMedewerker)
            ;
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
                ->leftJoin('izKlant.hulpvragen', 'actieveHulpvraag', 'WITH', $builder->expr()->andX(
                    'actieveHulpvraag.hulpaanbod IS NULL',
                    'actieveHulpvraag.einddatum IS NULL OR actieveHulpvraag.einddatum >= :now'
                ))
                ->addGroupBy('izKlant.id')
                ->andHaving('COUNT(actieveHulpvraag) = 0')
                ->setParameter('now', new \DateTime())
            ;
            $ids = $this->getIds($subBuilder);
            $builder
                ->andWhere('izKlant.id IN (:zonder_actieve_hulpvraag)')
                ->setParameter('zonder_actieve_hulpvraag', $ids)
            ;
        }

        if ($this->zonderActieveKoppeling) {
            $subBuilder = $this->getSubBuilder($builder)
                ->select('izKlant.id')
                ->leftJoin('izKlant.hulpvragen', 'actieveKoppeling', 'WITH', $builder->expr()->andX(
                    'actieveKoppeling.hulpaanbod IS NOT NULL',
                    'actieveKoppeling.koppelingEinddatum IS NULL OR actieveKoppeling.koppelingEinddatum >= :now'
                ))
                ->addGroupBy('izKlant.id')
                ->andHaving('COUNT(actieveKoppeling) = 0')
                ->setParameter('now', new \DateTime())
            ;

            $builder
                ->andWhere('izKlant.id IN (:zonder_actieve_koppeling)')
                ->setParameter('zonder_actieve_koppeling', $this->getIds($subBuilder))
            ;
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
        $query = $builder->getQuery();
        $query
            ->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, UseIndexSqlWalker::class);
        $query
            ->setHint(UseIndexSqlWalker::class, [
                IndexHint::use(Hulpvraag::IDX_DEELNEMER_DISCR_DELETED_EINDDATUM_KOPPELING, Hulpvraag::TABLE_NAME),
                IndexHint::use(IzDeelnemer::IDX_ID_AFSLUITING_DELETED_MODEL, IzDeelnemer::TABLE_NAME)
                ])
            ;
        $sql = SqlExtractor::getFullSQL($query);

        $result = $query->getResult();
        return array_map(
            function (array $item) {
                return $item['id'];
            }, $result
        );
    }
}
