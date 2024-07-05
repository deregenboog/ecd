<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use HsBundle\Entity\Activiteit;

class ActiviteitDao extends AbstractDao implements ActiviteitDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'activiteit.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'activiteit.id',
            'activiteit.naam',
        ],
    ];

    protected $class = Activiteit::class;

    protected $alias = 'activiteit';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klus")
            ->leftJoin("{$this->alias}.klussen", 'klus')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Activiteit $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Activiteit $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Activiteit $entity)
    {
        $this->doDelete($entity);
    }
}
