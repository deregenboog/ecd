<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Doorverwijzing;

class DoorverwijzingDao extends AbstractDao implements DoorverwijzingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'doorverwijzing.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'doorverwijzing.naam',
            'doorverwijzing.startdatum',
            'doorverwijzing.einddatum',
        ],
    ];

    protected $class = Doorverwijzing::class;

    protected $alias = 'doorverwijzing';

    public function create(Doorverwijzing $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Doorverwijzing $entity)
    {
        $this->doUpdate($entity);
    }
}
