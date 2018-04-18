<?php

namespace IzBundle\Service;

use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Koppelingstatus;

class KoppelingstatusDao extends AbstractDao implements KoppelingstatusDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'koppelingstatus.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'koppelingstatus.id',
            'koppelingstatus.naam',
            'koppelingstatus.actief',
        ],
    ];

    protected $class = Koppelingstatus::class;

    protected $alias = 'koppelingstatus';

    public function create(Koppelingstatus $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Koppelingstatus $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Koppelingstatus $entity)
    {
        $this->doDelete($entity);
    }
}
