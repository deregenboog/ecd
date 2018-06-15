<?php

namespace IzBundle\Service;

use AppBundle\Service\AbstractDao;
use IzBundle\Entity\BinnengekomenVia;

class BinnengekomenViaDao extends AbstractDao implements BinnengekomenViaDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'binnengekomenVia.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'binnengekomenVia.id',
            'binnengekomenVia.naam',
            'binnengekomenVia.actief',
        ],
    ];

    protected $class = BinnengekomenVia::class;

    protected $alias = 'binnengekomenVia';

    public function create(BinnengekomenVia $entity)
    {
        $this->doCreate($entity);
    }

    public function update(BinnengekomenVia $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(BinnengekomenVia $entity)
    {
        $this->doDelete($entity);
    }
}
