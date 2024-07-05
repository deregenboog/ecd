<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use HsBundle\Entity\Herinnering;

class HerinneringDao extends AbstractDao implements HerinneringDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'herinnering.datum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'herinnering.referentie',
            'herinnering.datum',
            'herinnering.bedrag',
            'factuur.nummer',
        ],
    ];

    protected $class = Herinnering::class;

    protected $alias = 'herinnering';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, factuur, klant")
            ->innerJoin('herinnering.factuur', 'factuur')
            ->innerJoin('factuur.klant', 'klant')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Herinnering $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Herinnering $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Herinnering $entity)
    {
        $this->doDelete($entity);
    }
}
