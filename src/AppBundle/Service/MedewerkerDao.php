<?php

namespace AppBundle\Service;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

class MedewerkerDao extends AbstractDao implements MedewerkerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'medewerker.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'medewerker.id',
            'medewerker.achternaam',
            'medewerker.geboortedatum',
            'geslacht.volledig',
            'werkgebied.naam',
            'postcodegebied.naam',
        ],
    ];

    protected $class = Medewerker::class;

    protected $alias = 'medewerker';

    /**
     * @param int             $page
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
     * @return Medewerker
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * @param Medewerker $entity
     */
    public function create(Medewerker $entity)
    {
        return $this->doCreate($entity);
    }

    /**
     * @param Medewerker $entity
     */
    public function update(Medewerker $entity)
    {
        return $this->doUpdate($entity);
    }

    /**
     * @param Medewerker $entity
     */
    public function delete(Medewerker $entity)
    {
        return $this->doDelete($entity);
    }
}
