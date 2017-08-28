<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Dagdeel;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class DagdeelDao extends AbstractDao implements DagdeelDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'dagdeel.dagdeel',
            'dagdeel.datum',
            'project.naam',
        ],
    ];

    protected $class = Dagdeel::class;

    protected $alias = 'dagdeel';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select('dagdeel, project, traject, deelnemer, klant')
            ->innerJoin('dagdeel.project', 'project')
            ->innerJoin('dagdeel.traject', 'traject')
            ->innerJoin('traject.deelnemer', 'deelnemer')
            ->innerJoin('deelnemer.klant', 'klant')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Dagdeel $dagdeel)
    {
        $this->doCreate($dagdeel);
    }

    public function update(Dagdeel $dagdeel)
    {
        $this->doUpdate($dagdeel);
    }

    public function delete(Dagdeel $dagdeel)
    {
        $this->doDelete($dagdeel);
    }

    public function countByDeelnemer(\DateTime $startDate, \DateTime $endDate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.'.aanwezigheid, COUNT('.$this->alias.'.aanwezigheid) AS aantal, CONCAT_WS(\' \', klant.voornaam, klant.tussenvoegsel, klant.achternaam) AS naam')
            ->innerJoin($this->alias.'.traject', 'traject')
            ->innerJoin('traject.deelnemer', 'deelnemer')
            ->innerJoin('deelnemer.klant', 'klant')
            ->where($this->alias.'.datum BETWEEN :start_date AND :end_date')
            ->setParameters([
                'start_date' => $startDate,
                'end_date' => $endDate,
            ])
            ->groupBy($this->alias.'.aanwezigheid, deelnemer.id')
        ;

        return $builder->getQuery()->getResult();
    }
}
