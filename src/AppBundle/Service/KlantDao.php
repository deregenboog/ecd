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
     *
     * @return PaginationInterface
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
