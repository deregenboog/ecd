<?php

namespace AppBundle\Service;

use AppBundle\Entity\Zrm;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

class ZrmDao extends AbstractDao implements ZrmDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'zrm.created',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'zrm.created',
            'zrm.requestModule',
        ],
    ];

    protected $class = Zrm::class;

    protected $alias = 'zrm';

    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.klant", 'klant')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * @param int $id
     *
     * @return Zrm
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * @param Zrm $entity
     */
    public function create(Zrm $entity)
    {
        return $this->doCreate($entity);
    }

    /**
     * @param Zrm $entity
     */
    public function update(Zrm $entity)
    {
        return $this->doUpdate($entity);
    }

    /**
     * @param Zrm $entity
     */
    public function delete(Zrm $entity)
    {
        return $this->doDelete($entity);
    }
}
