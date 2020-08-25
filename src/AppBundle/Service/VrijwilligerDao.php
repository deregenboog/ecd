<?php

namespace AppBundle\Service;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

class VrijwilligerDao extends AbstractDao implements VrijwilligerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'vrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'vrijwilliger.id',
            'vrijwilliger.achternaam',
            'vrijwilliger.geboortedatum',
            'medewerker.voornaam',
            'geslacht.volledig',
            'werkgebied.naam',
            'postcodegebied.naam',
        ],
    ];

    protected $class = Vrijwilliger::class;

    protected $alias = 'vrijwilliger';

    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->leftJoin("{$this->alias}.medewerker", 'medewerker')
            ->leftJoin("{$this->alias}.geslacht", 'geslacht')
            ->leftJoin("{$this->alias}.werkgebied", 'werkgebied')
            ->leftJoin("{$this->alias}.postcodegebied", 'postcodegebied')
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
     * @return Vrijwilliger
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * @param Vrijwilliger $vrijwilliger
     */
    public function create(Vrijwilliger $vrijwilliger)
    {
        return $this->doCreate($vrijwilliger);
    }

    /**
     * @param Vrijwilliger $vrijwilliger
     */
    public function update(Vrijwilliger $vrijwilliger)
    {
        return $this->doUpdate($vrijwilliger);
    }

    /**
     * @param Vrijwilliger $vrijwilliger
     */
    public function delete(Vrijwilliger $vrijwilliger)
    {
        // @todo remove this when disabled field is no longer needed
        $vrijwilliger->setDisabled(true);
        $this->update($vrijwilliger);

        return $this->doDelete($vrijwilliger);
    }
}
