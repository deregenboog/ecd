<?php

namespace HsBundle\Service;

use HsBundle\Entity\Factuur;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

class FactuurDao extends AbstractDao implements FactuurDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'factuur.nummer',
        'defaultSortDirection' => 'desc',
        'wrap-queries' => true, // because of HAVING clause in filter
        'sortFieldWhitelist' => [
            'factuur.nummer',
            'factuur.datum',
            'factuur.bedrag',
            'klant.achternaam',
        ],
    ];

    protected $class = Factuur::class;

    protected $alias = 'factuur';

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin('factuur.klus', 'klus')
            ->innerJoin('klus.klant', 'klant')
            ->innerJoin('klant.klant', 'basisklant')
        ;

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
    public function create(Factuur $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Factuur $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Factuur $entity)
    {
        $this->doDelete($entity);
    }
}
