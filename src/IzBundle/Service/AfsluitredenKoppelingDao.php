<?php

namespace IzBundle\Service;

use AppBundle\Service\AbstractDao;
use IzBundle\Entity\AfsluitredenKoppeling;

class AfsluitredenKoppelingDao extends AbstractDao implements AfsluitredenKoppelingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'afsluitreden.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'afsluitreden.id',
            'afsluitreden.naam',
            'afsluitreden.actief',
        ],
    ];

    protected $class = AfsluitredenKoppeling::class;

    protected $alias = 'afsluitreden';

    public function create(AfsluitredenKoppeling $entity)
    {
        $this->doCreate($entity);
    }

    public function update(AfsluitredenKoppeling $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(AfsluitredenKoppeling $entity)
    {
        $this->doDelete($entity);
    }
}
