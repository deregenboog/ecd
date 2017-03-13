<?php

namespace HsBundle\Service;

use HsBundle\Entity\Vrijwilliger;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;

class VrijwilligerDao extends AbstractDao implements VrijwilligerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'basisvrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'vrijwilliger.id',
            'basisvrijwilliger.achternaam',
            'basisvrijwilliger.werkgebied',
        ],
    ];

    protected $class = Vrijwilliger::class;

    protected $alias = 'vrijwilliger';

    /**
     * {inheritdoc}
     */
    public function findAll($page = 1, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
            ->andWhere('basisvrijwilliger.disabled = false')
        ;

        if ($filter) {
            $filter->vrijwilliger->alias = 'basisvrijwilliger';
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
    public function create(Vrijwilliger $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function update(Vrijwilliger $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}
     */
    public function delete(Vrijwilliger $entity)
    {
        $this->doDelete($entity);
    }
}
