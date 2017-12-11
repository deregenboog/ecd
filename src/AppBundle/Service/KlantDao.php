<?php

namespace AppBundle\Service;

use AppBundle\Entity\Klant;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $class = Klant::class;

    protected $alias = 'klant';

    /**
     * @param int $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * @param FilterInterface $filter
     *
     * @return int
     */
    public function countAll(FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id)")
            ->where("{$this->alias}.disabled = false")
            ->orderBy("{$this->alias}.achternaam")
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        return $builder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param int $id
     *
     * @return Klant
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * @param Klant $klant
     */
    public function create(Klant $klant)
    {
        return $this->doCreate($klant);
    }

    /**
     * @param Klant $klant
     */
    public function update(Klant $klant)
    {
        return $this->doUpdate($klant);
    }

    /**
     * @param Klant $klant
     */
    public function delete(Klant $klant)
    {
        return $this->doDelete($klant);
    }
}
