<?php

namespace IzBundle\Filter;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\VrijwilligerFilter;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Doelgroep;
use IzBundle\Entity\Hulpvraagsoort;
use IzBundle\Entity\Project;

class HulpaanbodFilter implements FilterInterface
{
    /**
     * @var bool
     */
    public $matching = true;

    /**
     * @var \DateTime
     */
    public $startdatum;

    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

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
     * @var string
     */
    public $zoekterm;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }

        if ($this->startdatum) {
            $builder
                ->andWhere('hulpaanbod.startdatum = :startdatum')
                ->setParameter('startdatum', $this->startdatum)
            ;
        }

        if ($this->project) {
            $builder
                ->andWhere('hulpaanbod.project = :project')
                ->setParameter('project', $this->project)
            ;
        }

        if ($this->hulpvraagsoort) {
            if (!in_array('hulpvraagsoort', $builder->getAllAliases())) {
                $builder->innerJoin('hulpaanbod.hulpvraagsoorten', 'hulpvraagsoort');
            }
            $builder
                ->andWhere('hulpvraagsoort = :hulpvraagsoort')
                ->setParameter('hulpvraagsoort', $this->hulpvraagsoort)
            ;
        }

        if ($this->doelgroep) {
            if (!in_array('doelgroep', $builder->getAllAliases())) {
                $builder->innerJoin('hulpaanbod.doelgroepen', 'doelgroep');
            }
            $builder
                ->andWhere('doelgroep = :doelgroep')
                ->setParameter('doelgroep', $this->doelgroep)
            ;
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('hulpaanbod.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }

        if ($this->zoekterm) {
            $zoektermen = array_values(array_filter(
                explode(' ', $this->zoekterm),
                function ($zoekterm): bool {
                    return '' !== trim($zoekterm);
                }
            ));
            foreach ($zoektermen as $i => $zoekterm) {
                if (0 === $i) {
                    $builder->leftJoin('hulpaanbod.verslagen', 'verslag');
                }
                $builder
                    ->andWhere($builder->expr()->orX(
                        'hulpaanbod.info LIKE :zoekterm_'.$i,
                        'verslag.opmerking LIKE :zoekterm_'.$i,
                    ))
                    ->setParameter('zoekterm_'.$i, '%'.$zoekterm.'%')
                ;
            }
        }
    }
}
