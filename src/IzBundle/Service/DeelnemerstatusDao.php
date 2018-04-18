<?php

namespace IzBundle\Service;

use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Deelnemerstatus;

class DeelnemerstatusDao extends AbstractDao implements DeelnemerstatusDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'deelnemerstatus.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'deelnemerstatus.id',
            'deelnemerstatus.naam',
            'deelnemerstatus.actief',
        ],
    ];

    protected $class = Deelnemerstatus::class;

    protected $alias = 'deelnemerstatus';

    public function create(Deelnemerstatus $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Deelnemerstatus $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Deelnemerstatus $entity)
    {
        $this->doDelete($entity);
    }
}
