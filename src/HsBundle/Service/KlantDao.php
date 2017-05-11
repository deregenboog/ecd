<?php

namespace HsBundle\Service;

use HsBundle\Entity\Klant;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'wrap-queries' => true, // because of HAVING clause in filter
        'sortFieldWhitelist' => [
            'klant.actief',
            'klant.id',
            'klant.achternaam',
            'klant.werkgebied',
        ],
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        if ($filter) {
            $filter->applyTo($builder);
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
    }

    /**
     * {inheritdoc}
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}
     */
    public function create(Klant $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Klant $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Klant $entity)
    {
        $this->doDelete($entity);
    }

    /**
     * {inheritdoc}
     */
    public function countByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('klant')
            ->select('COUNT(DISTINCT(klant.id)) AS aantal, klant.werkgebied AS stadsdeel')
            ->innerJoin('klant.klussen', 'klus')
            ->groupBy('klant.werkgebied')
        ;

        if ($start) {
            $builder->andWhere('klus.startdatum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('klus.startdatum <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * {inheritdoc}
     */
    public function countNewByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('klant')
            ->select('COUNT(DISTINCT(klant.id)) AS aantal, klant.werkgebied AS stadsdeel')
            ->groupBy('klant.werkgebied')
        ;

        if ($start) {
            $builder->andWhere('klant.inschrijving >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('klant.inschrijving <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }
}
