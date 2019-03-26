<?php

namespace AppBundle\Service;

use AppBundle\Entity\Land;
use AppBundle\Filter\FilterInterface;

class LandDao extends AbstractDao implements LandDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'land.land',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'land.land',
            'land.afkorting2',
            'land.afkorting3',
        ],
    ];

    protected $class = Land::class;

    protected $alias = 'land';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * @param int $id
     *
     * @return Land
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * @param Land $land
     */
    public function create(Land $land)
    {
        return $this->doCreate($land);
    }

    /**
     * @param Land $land
     */
    public function update(Land $land)
    {
        return $this->doUpdate($land);
    }

    /**
     * @param Land $land
     */
    public function delete(Land $land)
    {
        return $this->doDelete($land);
    }
}
