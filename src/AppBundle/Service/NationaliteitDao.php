<?php

namespace AppBundle\Service;

use AppBundle\Entity\Nationaliteit;
use AppBundle\Filter\FilterInterface;

class NationaliteitDao extends AbstractDao implements NationaliteitDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'nationaliteit.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'nationaliteit.naam',
        ],
    ];

    protected $class = Nationaliteit::class;

    protected $alias = 'nationaliteit';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * @param int $id
     *
     * @return Nationaliteit
     */
    public function find($id)
    {
        return parent::find($id);
    }

    public function create(Nationaliteit $nationaliteit)
    {
        return $this->doCreate($nationaliteit);
    }

    public function update(Nationaliteit $nationaliteit)
    {
        return $this->doUpdate($nationaliteit);
    }

    public function delete(Nationaliteit $nationaliteit)
    {
        return $this->doDelete($nationaliteit);
    }
}
