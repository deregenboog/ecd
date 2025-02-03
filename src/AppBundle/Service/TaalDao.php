<?php

namespace AppBundle\Service;

use AppBundle\Entity\Taal;
use AppBundle\Filter\FilterInterface;

class TaalDao extends AbstractDao implements TaalDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'taal.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'taal.naam',
        ],
    ];

    protected $class = Taal::class;

    protected $alias = 'taal';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * @param int $id
     *
     * @return Taal
     */
    public function find($id)
    {
        return parent::find($id);
    }

    public function create(Taal $taal)
    {
        return $this->doCreate($taal);
    }

    public function update(Taal $taal)
    {
        return $this->doUpdate($taal);
    }

    public function delete(Taal $taal)
    {
        return $this->doDelete($taal);
    }
}
