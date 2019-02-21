<?php

namespace MwBundle\Service;

use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Trajecthouder;

class TrajecthouderDao extends AbstractDao implements TrajecthouderDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'trajecthouder.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'trajecthouder.naam',
            'trajecthouder.startdatum',
            'trajecthouder.einddatum',
        ],
    ];

    protected $class = Trajecthouder::class;

    protected $alias = 'trajecthouder';

    public function create(Trajecthouder $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Trajecthouder $entity)
    {
        $this->doUpdate($entity);
    }
}
