<?php

namespace AppBundle\Service;

use AppBundle\Entity\Land;
use AppBundle\Entity\Doelstelling;
use AppBundle\Filter\FilterInterface;

class DoelstellingDao extends AbstractDao
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'doelstelling.repository',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'doelstelling.repository',
            'doelstelling.categorie',
            'doelstelling.kpi',
        ],
    ];

    protected $class = Doelstelling::class;

    protected $alias = 'doelstelling';

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
     * @param Doelstelling $prestatie
     */
    public function create(Doelstelling $prestatie)
    {
        return $this->doCreate($prestatie);
    }

    /**
     *@param Doelstelling $prestatie
     */
    public function update(Doelstelling $prestatie)
    {
        return $this->doUpdate($prestatie);
    }

    /**
     * @param Doelstelling $prestatie
     */
    public function delete(Doelstelling $prestatie)
    {
        return $this->doDelete($prestatie);
    }
}
